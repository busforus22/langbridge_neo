<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\Message;

class AdminReportController extends Controller
{
    /** report index */
    //users
    public function users(Request $request)
{
    $query = Report::where('reported_content_type', User::class)
        ->with('reporter.profile');

    if ($request->filled('q')) {
        $keyword = $request->q;

        // Report 自体の ID や content_id は残す（必要なら）
        $query->where(function ($q) use ($keyword) {
            $q->where('id', $keyword)
              ->orWhere('reported_content_id', $keyword)
              ->orWhere('detail', 'LIKE', "%{$keyword}%");
        

        // Reporter の handle のみ検索
        $q->orWhereHas('reporter.profile', function ($q2) use ($keyword) {
            $q2->where('handle', 'LIKE', "%{$keyword}%");
        });
    });
    }

    $reports = $query->orderBy('created_at', 'desc')->paginate(10);

    return view('admin.report.user', compact('reports'));
}

    // message
    public function messages(Request $request)
{
    $query = Report::where('reported_content_type', Message::class)
        ->with(['reporter.profile', 'message']);

    if ($request->filled('q')) {
        $keyword = $request->q;

        $query->where(function ($q) use ($keyword) {
            $q->where('id', $keyword)
              ->orWhere('reported_content_id', $keyword)
              ->orWhere('detail', 'LIKE', "%{$keyword}%");
        

        // Reporter の handle 検索
        $q->orWhereHas('reporter.profile', function ($q2) use ($keyword) {
            $q2->where('handle', 'LIKE', "%{$keyword}%");
        });
    });
    }

    $reports = $query->orderBy('created_at', 'desc')->paginate(10);

    return view('admin.report.message', compact('reports'));
}


    /** Action to report */
    public function action(Request $request, $id){
    $report = Report::findOrFail($id);
    
   
    $target = $report->reportedContent;

    
    if (!$target) {
        return back()->with('error', 'not found');
    }

    // User
    if ($target instanceof \App\Models\User) {
        
        $currentStatus = $report->action_status;

        if ($currentStatus === 'pending') {
            $report->action_status = 'warn';
        } elseif ($currentStatus === 'warn') {
            $target->update(['suspended' => true]);
            $report->action_status = 'suspend';
        } elseif ($currentStatus === 'suspend') {
            $target->delete(); 
            $report->action_status = 'user_deleted'; 
        } elseif ($currentStatus === 'user_deleted') {
            $target->restore(); 
            $report->action_status = 'restore';
        } elseif ($currentStatus === 'restore') {
            $target->update(['suspended' => false]);
            $report->action_status = 'pending'; 
        }
    } 
    //  Message
    elseif ($target instanceof \App\Models\Message) {
        if ($report->action_status === 'pending') {
            $target->forceDelete();
            $report->action_status = 'deleted';
        }
    }

    
    $report->save();

    return redirect()->back()->with('success', $report->action_status);
}

}
