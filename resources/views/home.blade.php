@extends('layouts.app')

@section('content')

@php
    use Carbon\Carbon;

    $start = Carbon::parse($fromDate);
    $end = Carbon::parse($today);

    function contributionLevel(int $count): string
        {
            return match (true) {
                $count === 0 => 'level-0',
                $count < 5   => 'level-1',
                $count < 10  => 'level-2',
                $count < 20  => 'level-3',
                default      => 'level-4',
            };
        }
@endphp

<div class="container home-main">

    {{-- page header --}}
    <div class="home-header">
        <h1 class="logo-title">
            <img src="{{ asset('images/logo.png') }}" alt="Site Logo" class="logo-img">
            LangBridge
        </h1>
        <p class="subtitle">Connect through language. Learn through friendship.</p>
    </div>

    <div class="home-body">
        {{-- Learning Journey (Streak Calendar placeholder), 単語クイズ参加するとマークされる --}}

        <section class="learning-journey">
            <h2 class="section-title">{{__('messages.learning_journey')}}</h2>
            <div class="streak-calendar">
                <div class="row mb-1">
                    <p class="col">Current streak: {{ $streak ?? 0 }} days</p>
                    <p class="col">Max streak: {{ $maxStreak ?? 0 }} days</p>
                </div>
                <table class="calendar-table">
                    <thead>
                        <tr>
                            <th>Sun</th>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($calendar as $week)
                        <tr>
                            @foreach($week as $date)
                            @if($date)
                            <td class="{{ isset($datesSet[$date]) ? 'active' : '' }}">{{
                                \Carbon\Carbon::parse($date)->day }}</td>
                            @else
                            <td></td>
                            @endif
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Connections --}}
        <section class="connections">

            {{-- Recent Chat --}}
            <section class="recent-chats-section">
                <h2 class="section-title">{{__('messages.recent_chats')}}</h2>

                <div class="chat-list">
                    @foreach($recentChats as $user)
                    <div class="chat-card">
                        <a href="{{ route('chat.pages.chat', ['to_user_id' => encrypt($user->id)]) }}">
                            @if($user->profile && $user->profile->avatar)
                            <img src="{{ $user->profile->avatar }}" class="avatar rounded-circle">
                            @else
                            <i class="fa-solid fa-circle-user text-secondary icon-bd"></i>
                            @endif
                        </a>
                        <div class="name">{{ $user->profile->nickname }}</div>
                        <div class="handle">{{ $user->profile->handle }}</div>
                    </div>
                    @endforeach
                </div>
            </section>


            {{-- Suggested Users --}}
            <section class="suggested-users-section">
                <h2 class="section-title">{{__('messages.suggested_users')}}</h2>
                <div class="suggested-list">
                    @foreach($otherUsers as $user)
                    <div class="suggested-card">
                        @if($user->profile->avatar)
                        <a href="{{ route('profile.show', ['user_id' => encrypt($user->id)]) }}">
                            <img src="{{ $user->profile->avatar }}" alt="Avatar" class="avatar rounded-circle">
                        </a>
                        @else
                        <a href="{{ route('profile.show', ['user_id' => encrypt($user->id)]) }}">
                            <i class="fa-solid fa-circle-user text-secondary icon-bd"></i>
                        </a>
                        @endif
                        <div class="suggested-info">
                            <p class="name">{{ $user->profile->nickname }}</p>
                            <p class="handle">{{ $user->profile->handle }}</p>
                            <p class="bio">{{ $user->profile->bio }}</p>
                            <button class="chat-btn"
                                onclick="window.location.href='{{ route('chat.pages.chat', ['to_user_id' => encrypt($user->id)]) }}'">
                                <i class="fa-regular fa-comment"></i> {{__('messages.start_chat')}}
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

        </section>

    </div>
</div>
@endsection