@extends('admin.layout')

@section('admin-content')

{{--  search bar --}}
<div style="align-items: center; margin-bottom: 20px; padding-right: 2px;">
    <form action="{{ route('admin.reports.messages') }}" method="GET" style="position: relative; display: inline-block;">
        <span style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #D1D5DB; pointer-events: none;">
            <i class="fas fa-search" style="font-size: 14px;"></i>
        </span>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search Message Report" 
               class="report-search-bar">
    </form>    
</div>
<table class="table-auto w-full border">
    <thead>
        <tr class="report-title">
            <th class="px-4 py-2 border">ID</th>
            <th class="px-4 py-2 border">Type</th>
            <th class="px-4 py-2 border">Reported Content ID</th>
            <th class="px-4 py-2 border">Violation Reason</th>
            <th class="px-4 py-2 border">Detail</th>
            <th class="px-4 py-2 border">File</th>
            <th class="px-4 py-2 border">Reported At</th>
            <th class="px-4 py-2 border">Reporter</th>
            <th class="px-4 py-2 border">Action</th>
        </tr>
    </thead>
    <tbody class="text-center">
        @foreach($reports as $report)
        <tr>
            <td class="px-4 py-2 border">{{ $report->id }}</td>
            <td class="px-4 py-2 border">{{ class_basename($report->reported_content_type) }}</td>
            <td class="px-4 py-2 border">
                @if($report->message)
                @php
        // メッセージの相手を判定（自分が送信者か受信者か）
                 $otherUserId = $report->message->user_id === auth()->id()
                    ? $report->message->to_user_id
                    : $report->message->user_id;
                @endphp

                <a href="{{ route('chat.pages.chat', [
                'id' => encrypt($otherUserId),
                'message_id' => $report->reported_content_id
                ]) }}"
                class="text-indigo-600 hover:text-indigo-800 font-bold flex items-center gap-1">
                <span>{{ $report->reported_content_id }}</span>
                </a>
                @else
                <span class="text-gray-400 text-xs">No Data</span>
                @endif
            </td>
            <td class="px-4 py-2 border">{{ $report->violation_reason_id }}</td>
            <td class="px-4 py-2 border">{{ $report->detail }}</td>
            <td class="px-4 py-2 border">
                @if(!empty($report->file))  
            <a href="{{ asset('storage/' . $report->file)}}" 
             target="_blank" 
             class="text-gray-600 hover:text-blue-500 transition-colors"
             title="View attached file">
            <i class="fa-solid fa-paperclip"></i>
            </a>
            @else
            <span class="text-gray-300">-</span>
            @endif
            </td>
            <td class="px-4 py-2 border">{{ $report->created_at }}</td>
            <td class="px-4 py-2 border">{{ $report->reporter->profile->handle }}</td>
            <td class="px-4 py-2 border">
                @if($report->reportedContent && $report->action_status !== 'deleted')
    
                <form action="{{ route('admin.reports.action', $report->id) }}" method="POST" 
                onsubmit="return confirm('Are you sure you want to delete this permanently?');">
                @csrf
                <button type="submit" name="action" value="delete"
                class="bg-white">
                <i class="fa-solid fa-trash-can text-danger"></i>
                </button>
                </form>
                @else
                <span class="text-secondry text-sm font-bold uppercase tracking-wider">Deleted</span>
                @endif
                
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
