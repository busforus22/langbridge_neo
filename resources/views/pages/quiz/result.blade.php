@extends('layouts.app')

@section('title', __('messages.quiz_result'))

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-body text-center py-5">

                    <h1 class="mb-4">{{ __('messages.quiz_result') }}</h1>

                    <!-- 正答数 / 問題数 -->
                    <h3 class="mb-4">
                        {{ session('correct') }} / {{ session('total') }}
                    </h3>

                    <!-- もう一度挑戦 -->
                    <a href="{{ route('quiz.settings') }}" class="btn btn-primary btn-lg mb-3">
                        {{ __('messages.quiz_try_again') }}
                    </a>

                    <br>

                    <!-- 単語リストへ -->
                    <a href="{{ route('vocabulary.index') }}" class="btn btn-yellow btn-lg">
                        {{ __('messages.go_list') }}
                    </a>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
