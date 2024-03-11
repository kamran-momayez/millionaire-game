@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Questions</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="{{ route('admin.questions.create') }}" class="btn btn-success">Create Question</a>
                        </div>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Text</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($questions as $question)
                                <tr>
                                    <td>{{ $question->id }}</td>
                                    <td>{{ $question->text }}</td>
                                    <td>{{ $question->points }}</td>
                                    <td>
                                        <a href="{{ route('admin.questions.edit', $question->id) }}" class="btn btn-primary">Edit</a>
                                        <form action="{{ route('admin.questions.destroy', $question->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
