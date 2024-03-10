@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Question</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.questions.update', $question->id) }}" method="post">
            @csrf
            @method('PUT')

            <div>
                <label for="text">Question Text:</label>
                <input type="text" name="text" id="text" value="{{ $question->text }}">
            </div>

            <div>
                <label for="points">Points</label>
                <input type="number" name="points" id="points" value="{{ old('points', $question->points ?? '') }}"
                       required>
            </div>

            <div id="answers-container">
                <label for="answers-container">Answers:</label>
                @foreach($question->answers as $answer)
                    <div class="answer">
                        <input type="text" name="answers[{{ $answer->id }}][text]" value="{{ $answer->text }}">
                        <input type="checkbox"
                               name="answers[{{ $answer->id }}][is_correct]" {{ $answer->is_correct ? 'checked' : '' }}>
                        <button type="button" class="btn-remove-answer">Remove</button>
                    </div>
                @endforeach
            </div>
            <button type="button" id="btn-add-answer">Add Answer</button>

            <button type="submit">Update Question</button>
        </form>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/add-remove-button.js') }}"></script>
@endpush

