@extends('layouts.game')

@section('title', $quiz->title)

@section('content')
    <div class="card">
        <span class="badge">{{ $quiz->questions->count() }} questions</span>
        <h1 style="margin-top: 12px;">{{ $quiz->title }}</h1>
        <p class="muted">{{ $quiz->description ?: 'No description yet.' }}</p>
        <form method="POST" action="{{ route('quizzes.sessions.store', $quiz) }}" style="margin-top: 20px;">
            @csrf
            <button type="submit" class="button success">Launch live session</button>
        </form>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <h2>Add a question</h2>
            <form method="POST" action="{{ route('quizzes.questions.store', $quiz) }}">
                @csrf
                <label for="prompt">Question prompt</label>
                <textarea id="prompt" name="prompt" rows="4" required>{{ old('prompt') }}</textarea>
                <div class="grid grid-2">
                    <div>
                        <label for="time_limit_seconds">Time limit (seconds)</label>
                        <input id="time_limit_seconds" name="time_limit_seconds" type="number" min="5" max="120" value="{{ old('time_limit_seconds', 20) }}" required>
                    </div>
                    <div>
                        <label for="points">Points</label>
                        <input id="points" name="points" type="number" min="100" max="5000" value="{{ old('points', 1000) }}" required>
                    </div>
                </div>
                <label for="option_a">Option A</label>
                <input id="option_a" name="option_a" type="text" value="{{ old('option_a') }}" required>
                <label for="option_b">Option B</label>
                <input id="option_b" name="option_b" type="text" value="{{ old('option_b') }}" required>
                <label for="option_c">Option C</label>
                <input id="option_c" name="option_c" type="text" value="{{ old('option_c') }}" required>
                <label for="option_d">Option D</label>
                <input id="option_d" name="option_d" type="text" value="{{ old('option_d') }}" required>
                <label for="correct_option">Correct option</label>
                <select id="correct_option" name="correct_option" required>
                    <option value="a">A</option>
                    <option value="b">B</option>
                    <option value="c">C</option>
                    <option value="d">D</option>
                </select>
                <button type="submit" class="button">Add question</button>
            </form>
        </div>

        <div class="card">
            <h2>Questions</h2>
            @forelse ($quiz->questions as $question)
                <div style="padding: 14px 0; border-bottom: 1px solid #e2e8f0;">
                    <strong>{{ $loop->iteration }}. {{ $question->prompt }}</strong>
                    <p class="muted">{{ $question->time_limit_seconds }} seconds · {{ $question->points }} points</p>
                    <ul class="list">
                        @foreach ($question->options as $option)
                            <li>{{ $option->option_text }} @if ($option->is_correct)<span class="badge">Right answer</span>@endif</li>
                        @endforeach
                    </ul>
                </div>
            @empty
                <p class="muted">No questions yet. Add the first one on the left.</p>
            @endforelse
        </div>
    </div>

    <div class="card">
        <h2>Live sessions launched from this quiz</h2>
        @forelse ($quiz->sessions as $session)
            <div style="padding: 10px 0; border-bottom: 1px solid #e2e8f0;">
                <strong>PIN {{ $session->pin }}</strong>
                <span class="badge">{{ $session->status }}</span>
                <div style="margin-top: 10px;"><a href="{{ route('sessions.show', $session) }}" class="button secondary">Open session</a></div>
            </div>
        @empty
            <p class="muted">No live sessions yet.</p>
        @endforelse
    </div>
@endsection
