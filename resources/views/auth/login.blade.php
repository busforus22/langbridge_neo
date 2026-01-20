@extends('layouts.auth')

@section('content')
<div class="container d-flex justify-content-center align-items-center" >
    <div class="card login-card p-4 mt-5">
                <div class="text-center mb-4">
                    <h2 class="mt-2 fw-semibold">Welcome to LangBridge</h2>
                </div>
                <!--Login form -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3 ">
                           
                            <div class="cute-input-wrapper mb-3">
                                <i class="fa-solid fa-envelope cute-icon"></i>
                            {{-- <span class="input-group-text bg-white border-0"><i class="fa-solid fa-envelope cute-icon"></i></span> --}}
                            <input id="email" type="email" class="form-control cute-input ps-5 @error('email') is-invalid @enderror" name="email" placeholder="email" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class=" mb-3 ">                          
                          <div class="cute-input-wrapper mb-2">
                            <i class="fa-solid fa-key cute-icon"></i>  
                            <input id="password" type="password" class="form-control cute-input ps-5 @error('password') is-invalid @enderror" name="password" placeholder="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="d-flex justify-content-beween align-item-center mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label mx-2 mt-1" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            
                                <br>
                                @if (Route::has('password.request'))
                                <a class="text-decoration-none mt-1" href="{{ route('password.request') }}">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>

                        <!-- login button -->
                                <button type="submit" class="btn login-btn w-100">
                                    {{ __('Login') }}
                                </button>

                        <!-- Register Link -->
                                <p class="text-center mb-0">
                                Don't have an account? <a href="{{ route('register') }}">Register</a>
                                </p>
                     </form>
    </div>

    @if(session('deleted'))
    <!-- 感謝モーダルを開いた状態で表示 -->
    <div class="modal fade show" style="display:block;" tabindex="-1" aria-labelledby="thankYouLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content text-center p-4">
          <h5 class="modal-title mb-3">Thank you for using our service</h5>
          <div class="modal-footer">
            <a href="{{ route('login') }}" class="btn btn-primary">Go to Login</a>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>
                   
@endsection
