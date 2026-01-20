<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Report;
use App\Models\ReportViolationReason;
use Illuminate\Support\Facades\Crypt;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::id();

        $users = User::where('users.id', '!=', $user_id)
            ->join('messages', function ($join) use ($user_id) {
                $join->on('users.id', '=', 'messages.user_id')
                    ->orOn('users.id', '=', 'messages.to_user_id');
            })
            ->where(function ($q) use ($user_id) {
                $q->where('messages.user_id', $user_id)
                    ->orWhere('messages.to_user_id', $user_id);
            })
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('MAX(messages.sent_at) as last_chat')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('last_chat')
            ->get();

        $toUser = null;

        $to_user_id = $request->input('to_user_id');

        if ($to_user_id) {
            try {
                $to_user_id = Crypt::decrypt($to_user_id);
            } catch (\Exception $e) {
                abort(403, 'Invalid user ID');
            }
        }

        // hiddenユーザーでも、過去にチャット履歴があれば非hiddenユーザーから再開できる
        if ($to_user_id) {
            $toUser = User::with('profile')->find($to_user_id);
            $exists = $toUser && $toUser->profile;
            $isHidden =  $toUser->profile->hidden;

            // hiddenユーザーとのチャットアクセス権限チェック
            if ($exists) {
                // 相手がhiddenの場合、チャット履歴があれば会話可能
                if ($isHidden) {
                    // チャット履歴があるか確認
                    $hasHistory = Message::where(function ($q) use ($user_id, $to_user_id) {
                        $q->where('user_id', $user_id)->where('to_user_id', $to_user_id);
                    })->orWhere(function ($q) use ($user_id, $to_user_id) {
                        $q->where('user_id', $to_user_id)->where('to_user_id', $user_id);
                    })->exists();

                    if (!$hasHistory) {
                        abort(403, 'This user is not available for chat (no history).');
                    }
                }
            } else {
                // user doesn't exist or profile missing
                abort(403, 'This user is not available for chat (user or profile missing).');
            }
        }

        // checks passed, show chat page
        $violationReasons = ReportViolationReason::where('category', 'chat')->get();
        return view('pages.chat', compact('users', 'to_user_id', 'toUser', 'violationReasons'));
    }


    // send message
    public function send(Request $request)
    {
        // check if any of the fields are filled
        if (
            !$request->filled('content') &&
            !$request->filled('emoji') &&
            !$request->hasFile('image')
        ) {
            return response()->json(['error' => 'please enter a message, emoji, or image'], 422);
        }

        // if to_user_id is empty, return error
        $to_user_id = $request->input('to_user_id');
        if (empty($to_user_id)) {
            return response()->json(['error' => 'chat partner is not selected'], 422);
        }

        $message = new Message();
        $message->user_id = Auth::id();
        $message->to_user_id = $to_user_id;
        $message->content = $request->input('content');
        $message->emoji = $request->input('emoji');
        $message->sent_at = now();
        $message->is_read = false;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('assets/chat_images', 'public');
            $message->image_path = '/storage/' . $path;
        }

        $message->save();
        return response()->json(['message' => $message]);
    }


    // fetch messages
    public function fetch(Request $request)
    {
        $to_user_id = $request->input('to_user_id');
        $user_id = Auth::id();

        if (empty($to_user_id)) {
            return response()->json(['messages' => []]);
        }


        $messages = Message::where(function ($q) use ($user_id, $to_user_id) {
            $q->where('user_id', $user_id)->where('to_user_id', $to_user_id);
        })->orWhere(function ($q) use ($user_id, $to_user_id) {
            $q->where('user_id', $to_user_id)->where('to_user_id', $user_id);
        })->orderBy('sent_at', 'asc')->get();

        foreach ($messages as $msg) {
            $sender = User::with('profile')->find($msg->user_id);
            $receiver = User::with('profile')->find($msg->to_user_id);
            $msg->user_name = $sender->name ?? '';
            $msg->partner_name = $receiver->name ?? '';
            $msg->user_avatar = $sender->profile->avatar ?? '';
            // partner_avatarは「自分以外の相手」のアバター
            if ($msg->user_id == $user_id) {
                $msg->partner_avatar = $receiver->profile->avatar ?? '';
                $msg->partner_handle = $receiver->profile->handle ?? '';
            } else {
                $msg->partner_avatar = $sender->profile->avatar ?? '';
                $msg->partner_handle = $sender->profile->handle ?? '';
            }
            $msg->content = $msg->content ?? '';
            $msg->emoji = $msg->emoji ?? '';
            $msg->image_path = (!empty($msg->image_path) && $msg->image_path !== 'null') ? $msg->image_path : '';
        }

        // update unread messages to read
        Message::where('user_id', $to_user_id)
            ->where('to_user_id', $user_id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['messages' => $messages]);
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        if ($message->user_id !== Auth::id()) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $message->delete();
        return response()->json(['success' => true]);
    }


    public function report(Request $request, $id)
    {

        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'violation_reason_id' => 'required|exists:report_violation_reasons,id',
            'detail' => 'nullable|string',
            'file' => 'nullable|file|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // save report (base64方式)
        $fileBase64 = null;
        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $fileBase64 = 'data:' . $uploadedFile->getMimeType() . ';base64,' . base64_encode(file_get_contents($uploadedFile));
        }

        Report::create([
            'reporter_id' => auth()->id(),
            'category' => 'message',
            'violation_reason_id' => $request->violation_reason_id,
            'detail' => $request->detail,
            'file' => $fileBase64,
            'reported_content_id' => $id,
            'reported_content_type' => \App\Models\Message::class,
            'action_status' => 'pending',
        ]);

        return response()->json(['success' => true, 'message' => 'Report saved.']);
    }
}
