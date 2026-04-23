@extends('layouts.game')

@section('title', 'Host dashboard')

@section('content')
    <div class="card">
        <h1>Your host dashboard</h1>
        <p class="muted">Create quizzes, add questions, launch live sessions, or use the published demo quiz when available.</p>
        <a href="{{ route('quizzes.create') }}" class="button">Create a new quiz</a>
    </div>

    <div class="grid grid-2">
        @forelse ($quizzes as $quiz)
            <div class="card">
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <span class="badge">{{ $quiz->questions_count }} questions</span>
                    @if ($quiz->is_published)
                        <span class="badge">Demo ready</span>
                    @endif
                </div>
                <h2 style="margin-top: 12px;">{{ $quiz->title }}</h2>
                <p class="muted">{{ $quiz->description ?: 'No description yet.' }}</p>
                <a href="{{ route('quizzes.show', $quiz) }}" class="button secondary">Open quiz</a>
            </div>
        @empty
            <div class="card">
                <h2>No quizzes yet</h2>
                <p class="muted">Start by creating a quiz, then add questions and launch a game session.</p>
            </div>
        @endforelse
    </div>
@endsection
