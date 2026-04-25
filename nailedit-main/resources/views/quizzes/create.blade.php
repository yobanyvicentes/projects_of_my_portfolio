@extends('layouts.game')

@section('title', 'Create quiz')

@section('content')
    <div class="card" style="max-width: 760px; margin: 0 auto;">
        <h1>Create a quiz</h1>
        <p class="muted">Build the quiz shell first. Then you can add questions and launch a live session.</p>

        <form method="POST" action="{{ route('quizzes.store') }}">
            @csrf
            <label for="title">Quiz title</label>
            <input id="title" name="title" type="text" value="{{ old('title') }}" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>

            <label>
                <input type="checkbox" name="is_published" value="1" style="width:auto; margin-right: 8px;"> Publish immediately
            </label>

            <button type="submit" class="button">Create quiz</button>
        </form>
    </div>
@endsection
