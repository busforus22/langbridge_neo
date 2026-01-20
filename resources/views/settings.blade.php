@extends('layouts.app')

@section('title', __('messages.user_settings'))

@section('content')
<div class="container d-flex justify-content-center">
    <div class="settings-card-wrapper p-4 p-md-5">
    <h2>{{ __('messages.user_settings') }}</h2>
    <form method="POST" action="{{ route('setting.update') }}">
        @csrf
        @method('PUT')

        <div class="card p-4 mb-4 custom-card">
            <h5>{{ __('messages.personal_information') }}</h5>

            <div class="mb-3">
                <label for="name" class="form-label">email</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ $user->email }}">
            </div>

            <div class="mb-3">

            <h6 class="mt-4">{{ __('messages.personal_information_visibility') }}</h6>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="age_hidden" id="age_hidden" {{ $user->profile->age_hidden ? 'checked' : '' }}>
                <label class="form-check-label mx-1 mt-1" for="age_hidden">{{ __('messages.hide_age') }}</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="country_hidden" id="country_hidden" {{ $user->profile->country_hidden ? 'checked' : '' }}>
                <label class="form-check-label mx-1 mt-1" for="country_hidden">{{ __('messages.hide_country') }}</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="region_hidden" id="region_hidden" {{ $user->profile->region_hidden ? 'checked' : '' }}>
                <label class="form-check-label mx-1 mt-1" for="region_hidden">{{ __('messages.hide_region') }}</label>
            </div>
            <small class="text-muted">*{{ __('messages.this_information_will_be_visible_on_your_public_profile_if_unchecked') }}</small>
        </div>

        <div class="card p-4 mb-4 custom-card">
            <h5>{{ __('messages.search_settings') }}</h5>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="hidden" id="hidden" {{ $user->profile->hidden ? 'checked' : '' }}>
                <label class="form-check-label mx-1 mt-1" for="hidden">{{ __('messages.hide_my_profile_from_search_results') }}</label>
            </div>
            <small class="text-muted">*{{ __('messages.prevent_your_profile_from_appearing_in_user_searches_or_suggested_connections') }}</small>
        </div>

        <button type="submit" class="btn edit-btn">{{ __('messages.save_changes') }}</button>
    </form>

    <hr>

    <div class="text-center mt-5">
        <p>{{ __('messages.if_you_wish_to_delete_your_account_please_click_the_button_below') }}</p>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
            {{ __('messages.delete_account') }}
        </button>
    </div>
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">{{ __('messages.confirm_account_deletion') }}</h5>
                </div>
                <div class="modal-body">
                  {{ __('messages.are_you_sure_you_want_to_delete_your_account') }}
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                  <form method="POST" action="{{ route('user.delete') }}">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger">{{ __('messages.delete_account') }}</button>
                  </form>
                </div>
              </div>
            </div>
          </div>

</div>
</div>
@endsection
