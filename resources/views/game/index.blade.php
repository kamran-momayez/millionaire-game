@extends('layouts.app')

@section('content')
    <div class="card mb-3">
        <div class="card-header">Game</div>
        <div class="card-body">
            <form action="{{ route('game.answer') }}" method="POST">
                @csrf
                @foreach($questions as $question)
                    <div class="card mb-3">
                        <div class="card-header">{{ $question->text }}</div>
                        <div class="card-body">
                            <input type="hidden" name="answers[{{ $question->id }}][]" value="">
                            @foreach($question->answers as $answer)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="answers[{{ $question->id }}][]" value="{{ $answer->id }}"
                                           id="answer{{ $answer->id }}">
                                    <label class="form-check-label"
                                           for="answer{{ $answer->id }}">{{ $answer->text }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                <button type="submit" class="btn btn-primary">Submit Answers</button>
            </form>
        </div>
    </div>
@endsection
