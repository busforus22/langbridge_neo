@extends('layouts.app')
@section('content')

<h1>Profile Search</h1>

<!--  Search Form -->
<form method="GET" action="{{ route('users.search') }}" autocomplete="off">
    <input type="text" name="keyword" placeholder="Search by @handle, nickname, or bio"
        value="{{ request('keyword') }}">

    <h3>Interest Categories</h3>
    @foreach($interests as $interest)
    <label>
        <input type="checkbox" name="interests[]" value="{{ $interest->id }}" {{ in_array($interest->id,
        (array)request('interests')) ? 'checked' : '' }}>
        {{ $interest->name }}
    </label>
    @endforeach

    <button type="submit">Search</button>
</form>

<hr>

<!--  Results Display -->
@forelse($users as $user)
<div style="margin-bottom: 1em;">
    <a href="#">
        <strong>{{ $user->handle }}</strong>
    </a><br>

    @if($user->nickname)
    <div><strong>Nickname:</strong> {{ $user->nickname }}</div>
    @endif

    @if($user->bio)
    <div><strong>Bio:</strong> {{ $user->bio }}</div>
    @endif

    <div style="margin-top: 5px;">
        @foreach($user->user->interests as $interest)
        <span style="display: inline-block; background: #eef; padding: 2px 6px; margin: 2px; border-radius: 4px;">
            {{ $interest->name }}
        </span>
        @endforeach
    </div>
</div>
@empty
@if(request()->filled('keyword') || request()->filled('interests'))
<p>No matching profiles found.</p>
@endif
@endforelse

<!--  Pagination -->
@if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
{{ $users->appends(request()->query())->links() }}
@endif

@endsection