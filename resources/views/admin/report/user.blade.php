@extends('admin.layout')

@section('admin-content')
{{--  search bar --}}
<div style="align-items: center; margin-bottom: 20px; padding-right: 2px;">
    <form action="{{ route('admin.reports.users') }}" method="GET" style="position: relative; display: inline-block;">
        <span style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #D1D5DB; pointer-events: none;">
            <i class="fas fa-search" style="font-size: 14px;"></i>
        </span>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search User Report" 
               class="report-search-bar">
    </form>    
</div>
<div class="table-wrapper">
<table class="admin-table">
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
                @php
            $encryptedId = encrypt($report->reported_content_id);
                @endphp
 
           <a href="{{ route('profile.show', ['user_id' => $encryptedId]) }}" 
           class="text-cyan-600 hover:underline font-bold">
           {{ $report->reported_content_id }} 
           </a>
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

                @if(stripos($report->reported_content_type, 'user') !== false)
    <form action="{{ route('admin.reports.action', $report->id) }}" method="POST">
        @csrf
        
        
        @if($report->action_status === 'pending')
            <button type="submit" class="btn btn-outline-warning">
                warn
            </button>
            

        @elseif($report->action_status === 'warn')
            <button type="submit" class="btn btn-warning">
                Suspend
            </button>

        @elseif($report->action_status === 'suspend')
            <button type="submit" class="btn btn-danger">
                SoftDelete
            </button>

        @elseif($report->action_status === 'user_deleted')
            <button type="submit" class="btn btn-success">
                Restore
            </button>
            
        @elseif($report->action_status === 'restore')
            <button type="submit" class="btn btn-primary">
                active
            </button>

        @endif
    </form>
@endif
            </td>
            
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection
