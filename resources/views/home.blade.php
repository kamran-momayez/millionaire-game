@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Welcome to the Millionaire Game Home Page!</div>
                    <div class="card-body">
                        @isset($topScores[0])
                            <h3>Top Ten Players!</h3>
                            <ol>
                                @foreach($topScores as $score)
                                    <li>{{ $score->name }} : {{ $score->score }}</li>
                                @endforeach
                            </ol>
                        @endisset
                        @auth
                            Go and play the <a href="{{ route('game.index') }}">Game</a>!
                        @else
                            Please <a href="{{ route('register.index') }}">Register</a> or <a
                                href="{{ route('login.index') }}">Login</a> to the system.
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
