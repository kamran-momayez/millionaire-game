@extends('layouts.app')

@section('content')
    <div class="card mb-3">
        <div class="card-header">Game Result</div>
        <div class="card-body">
            <p>Your total score is: {{ $totalScore }}</p>
            <div>
                @foreach($feedback as $questionId => $message)
                    <p>Question {{ $loop->iteration }}: {{ $message }}</p>
                @endforeach
            </div>
        </div>
    </div>
@endsection
