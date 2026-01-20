@extends('layouts.app')

@section('title',__('messages.vocabulary_quiz'))

@section('content')
<div class="container">
    <h1>{{__('messages.vocabulary_quiz')}}</h1>

    @php
    $front = $question->front;
    $back = $question->back;
    $note = $question->note;

    // 出題面
    $questionText = ($side === 'front') ? $front : $back;
    $answerText = ($side === 'front') ? $back : $front;
    @endphp

    <div id="quiz-card" class="p-4 border rounded text-center">

        <div class="mt-4 text-muted">
            {{ $index + 1 }} / {{ $total }}
        </div>

        <h3 id="question-text" class="mb-5">{{ $questionText }}</h3>

        <div id="answer-text" class="d-none">
            <h3>{{ $answerText }}</h3>
            <p>{{ $note }}</p>
        </div>


        <button id="show-answer-btn" class="btn btn-primary mt-3">{{__('messages.show_answer')}}</button>

        <div id="self-check" class="d-none mt-3">
            <form id="correct-form" action="{{ route('quiz.record') }}" method="POST">
                @csrf
                <input type="hidden" name="vocabulary_id" value="{{ $question->id }}">
                <input type="hidden" name="is_correct" value="1">
                <input type="hidden" name="index" value="{{ $index }}">
                <button class="btn btn-yellow">{{__('messages.correct')}}</button>
            </form>

            <form id="incorrect-form" action="{{ route('quiz.record') }}" method="POST" class="mt-2">
                @csrf
                <input type="hidden" name="vocabulary_id" value="{{ $question->id }}">
                <input type="hidden" name="is_correct" value="0">
                <input type="hidden" name="index" value="{{ $index }}">
                <button class="btn"
                    style="background-color: #f48fb1; color: #fff;">{{__('messages.incorrect')}}</button>
            </form>
        </div>

    </div>
</div>

@endsection