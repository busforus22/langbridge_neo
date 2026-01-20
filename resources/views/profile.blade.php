@extends('layouts.app')

@push('scripts')
<script src="{{ asset('js/translate.js') }}" defer></script>
<script src="{{ asset('js/report.js') }}" defer></script>
@endpush

@section('content')
<div class="container">
    <h2 class="text-center logo-title"><img src="{{ asset('images/logo.png') }}" alt="Site Logo" class="logo-img">{{ __('messages.profile') }}</h2>
    <div class="profile-wrapper">
    <div class="profile-top">
        <div class="avatar-section">
        <label class="mb-3">Avatar</label>
        <div>
            @if($profile->avatar)
            <img src="{{ $profile->avatar }}" alt="Avatar" class="rounded-circle" width="120">
            @else
             <i class="fa-solid fa-circle-user text-secondary icon-bd" ></i>
            @endif
        </div>
        @if(auth()->id() !== $profile->user_id)
        <button type="button" 
        onclick="openUserReportModal('{{ $user->id }}', '{{ $user->name }}', '{{ $profile->avatar }}')"
        class="btn btn-sm btn-link">
        <i class="fa-solid fa-flag text-danger"></i>
        </button>
        @endif
        </div>
        <div class="input-columns">
        <div class="form-group">
            <label>{{ __('messages.nickname') }}</label>
            <div class="profile-value">{{ $profile->nickname }}</div>
        </div>
        <div class="form-group">
            <label>{{ __('messages.handle') }}</label>
            <div class="profile-value">{{ $profile->handle }}</div>
        </div>
        <div class="form-group">
            <label>{{ __('messages.japanese level') }}</label>
            <div class="profile-value">{{ $profile->JP_level_text }}</div>
        </div>
        <div class="form-group">
            <label>{{ __('messages.english level') }}</label>
            <div class="profile-value">{{ $profile->EN_level_text }}</div>
        </div>
        <div class="form-group">
            <label>{{ __('messages.age') }}</label>
            <div>
            @if($user->profile->age_hidden)
            <span class="text-muted">Private</span>
            @else
            {{ $user->age }}
            @endif
        </div>
        </div>
        <div class="form-group">
            <label>{{ __('messages.country') }}</label>
            <div>
            @if($user->profile->country_hidden)
            <span class="text-muted">Private</span>
            @else
            {{ $profile->user->country }}
            @endif
        </div>
        </div>
        <div class="form-group">
            <label>{{ __('messages.region') }}</label>
            <div>
            @if($user->profile->region_hidden)
            <span class="text-muted">Private</span>
            @else
            {{ $profile->user->region }}
            @endif
        </div>
        </div>
        <div class="form-group empty-cell"></div>
        <div class="form-group bio-group">
            <label>{{ __('messages.bio') }}</label>
            <button id="translate-btn" class="btn btn-sm btn-outline-secondary">
                <i class="fa fa-language"></i>
            </button>
            <p id="bio-text">{{ $profile->bio }}</p>
            <div id="translation-result" class="mt-2"></div>
        </div>
        </div>
    </div>
</div>

<div class="interest-section">
    <div class="interest-title">{{ __('messages.profile_interests') }}</div>
    <div class="interests-grid">
        @foreach($interests as $interest)
            <div class="interest-card {{ $user->interests->pluck('id')->contains($interest->id) ? 'selected' : '' }}">
                <div class="interest-card-content">{{ __('messages.interests.' . $interest->id) }}</div>
            </div>
        @endforeach
    </div>
</div>
</div>

       
            <div>
        @if (auth()->id() === $profile->user_id)
            <a href="{{ route('profile.edit') }}" class="edit-btn btn-outline-primary btn-sm" >{{ __('messages.edit') }}</a>
        @endif
    </div>
</div>


<!-- Three-step report modal -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header">
                <h4 class="modal-title">Report User</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <!-- Hidden: target message -->
                <input type="hidden" id="report_target_id">

                <!-- Step 1: Confirm -->
                <div id="report-step-1">
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                        <!-- message image (if any) -->
                        <span id="report_user_avatar_wrapper" class="me-3">
                            <img id="report_user_avatar" src=""
                            class="rounded-circle report-avatar">
                                           
                        </span>
                        <!-- message content -->
                        <div>
                            <strong class="text-muted d-block report-target-label">Target User</strong>
                            <p id="report_user_name" class="mb-0 fw-bold report-target-name"></p>
                        </div>
                    </div>
                    <p class="text-center">Are you sure you want to report this user?</p>
                </div>

                <!-- Step 2: Details -->
                <div id="report-step-2" class="hidden">
                    <div class="mb-3">
                        <div class="form-label mb-3 fw-bold">Please select a reason</div>
                        <select id="report_reason" name="violation_reason_id" class="form-select" required>
                            <option value="" selected disabled>{{ __('Select a reason') }}</option>
                            @foreach($violationReasons as $reason)
                            <option value="{{ $reason->id }}">
                                {{ $reason->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Additional details (optional)</label>
                        <textarea id="report_details" name="detail" class="form-control" rows="3"
                            placeholder="Please describe the detail..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Screenshot etc (optional)</label>
                        <input type="file" id="report_file" name="file" class="form-control" accept="image/*">
                    </div>
                </div>

                <!-- Step 3: Submitted -->
                <div id="report-step-3" class="hidden">
                    <p class="fw-bold">Your report has been submitted.</p>
                    <p class="mt-1"> Thank you for your cooperation!</p>
                </div>
            </div>

            <!-- Footer (dynamic buttons) -->
            <div class="modal-footer">
                <!-- Step 1 buttons -->
                <div id="report-footer-1">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="report-next-1">Next</button>
                </div>

                <!-- Step 2 buttons -->
                <div id="report-footer-2" class="hidden">
                    <button type="button" class="btn btn-secondary me-2" id="report-back-2">Back</button>
                    <button type="button" class="btn btn-danger" id="report-submit-2">Report</button>
                </div>

                <!-- Step 3 buttons -->
                <div id="report-footer-3" class="hidden">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 


