@extends('layouts.app')

@section('title', 'Profile')


@section('content')

<div class="container">
    <h2 class="text-center">{{ __('messages.edit_profile') }}</h2>
    <div class="profile-wrapper">

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="profile-top">
                <div class="avatar-section">
                    <div class="mb-3">
                        <label for="avatar-label" class="mb-3">Avatar</label>
                        <div>
                            @if($profile->avatar)
                            <img src="{{ old('avatar', $profile->avatar) }}" alt="Avatar" class="rounded-circle"
                                width="120">
                            @else
                            <i class="fa-solid fa-circle-user text-secondary icon-bd"></i>
                            @endif
                            <input type="file" name="avatar" class="form-control mt-5">
                        </div>
                    </div>
                </div>

                <div class="input-columns">
                    <div class="form-group">
                        <label>{{ __('messages.nickname') }}</label>
                        <input type="text" name="nickname" value="{{ old('nickname', $profile->nickname) }}"
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.handle') }}</label>
                        <input type="text" name="handle" value="{{ old('handle', $profile->handle) }}"
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.japanese level') }}</label>
                        <select name="JP_level" class="form-select">
                            @foreach (['Beginner', 'Intermediate', 'Advanced', 'Native'] as $level)
                            <option value="{{ $level }}" @selected($profile->JP_level === $level)>{{ $level }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.english level') }}</label>
                        <select name="EN_level" class="form-select">
                            @foreach (['Beginner', 'Intermediate', 'Advanced', 'Native'] as $level)
                            <option value="{{ $level }}" @selected($profile->EN_level === $level)>{{ $level }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.age') }}</label>
                        <input type="text" class="form-control" value="{{ $user->age }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.country') }}</label>
                        <input type="text" name="country" value="{{ old('country', $profile->user->country) }}"
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.region') }}</label>
                        <input type="text" name="region" value="{{ old('region', $profile->user->region) }}"
                            class="form-control">
                    </div>
                    <div class="form-group empty-cell"></div>
                    <div class="form-group bio-group">
                        <label>{{ __('messages.bio') }}</label>
                        <textarea name="bio" id="bio-text"
                            class="form-control">{{ old('bio', $profile->bio) }}</textarea>
                    </div>
                </div>
            </div>

    </div>
    <div class="interest-section">
        <div class="interest-title">{{ __('messages.profile_interests') }}</div>
        <div class="interests-grid">

            @foreach($interests as $interest)
            <label class="interest-card">
                <input type="checkbox" name="interests[]" value="{{ $interest->id }}" {{
                    $user->interests->pluck('id')->contains($interest->id) ? 'checked' : '' }}>
                <div class="interest-card-content">{{ __('messages.interests.' . $interest->id) }}</div>
            </label>
            @endforeach

        </div>
    </div>
    <button type="submit" class="edit-btn btn-primary">{{ __('messages.edit') }}</button>
    </form>
</div>

@endsection