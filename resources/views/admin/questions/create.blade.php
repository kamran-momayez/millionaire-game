@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create Question</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.questions.store') }}" method="post">
            @csrf

            <div>
                <label for="text">Question Text:</label>
                <input type="text" name="text" id="text">
            </div>

            <div>
                <label for="points">Points:</label>
                <input type="number" name="points" id="points" required>
            </div>

            <div id="answers-container">
                <label for="answers-container">Answers:</label>
            </div>
            <button type="button" id="btn-add-answer">Add Answer</button>

            <button type="submit">Create Question</button>
        </form>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/add-remove-button.js') }}"></script>
@endpush
