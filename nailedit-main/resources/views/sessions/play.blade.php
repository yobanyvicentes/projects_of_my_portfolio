@extends('layouts.game')

@section('title', 'Play '.$session->pin)
@section('head')
    @if ($session->status !== 'finished')
        <meta http-equiv="refresh" content="4">
    @endif
@endsection

@section('content')
    @php
        $rank = $leaderboard->search(fn ($leader) => $leader->id === $player->id);
        $rank = $rank === false ? null : $rank + 1;
    @endphp

    <div class="grid grid-2">
        <div class="card">
            <span class="badge">{{ $session->status }}</span>
            <h1 style="margin-top: 12px;">You are playing as {{ $player->nickname }}</h1>
            <p class="muted">Session PIN: {{ $session->pin }}</p>
            @if ($session->status !== 'finished')<p class="muted">This screen refreshes automatically every 4 seconds.</p>@endif
        </div>
        <div class="card">
            <h3>Your score</h3>
            <p style="font-size: 32px; font-weight: 700; margin: 8px 0 0;">{{ $player->score }}</p>
            <p class="muted" style="margin-top: 8px;">{{ $rank ? 'Current rank: #'.$rank : 'Rank will appear once scores are available.' }}</p>
        </div>
    </div>

    @if (! $currentQuestion || $session->status === 'lobby')
        <div class="card"><h2>Waiting in the lobby</h2><p class="muted">The host has not pushed a question yet. Keep this page open.</p></div>
    @elseif ($session->status === 'finished')
        <div class="card"><h2>Game finished</h2><p>Your final score is <strong>{{ $player->score }}</strong>. @if($rank)You finished at <strong>#{{ $rank }}</strong>.@endif</p></div>
    @else
        <div class="card">
            <h2>{{ $currentQuestion->prompt }}</h2>
            <p class="muted">{{ $currentQuestion->time_limit_seconds }} seconds · {{ $currentQuestion->points }} points</p>
            @if ($existingAnswer)
                <p><strong>You already submitted your answer for this question.</strong></p>
                <p class="muted">Result: {{ $existingAnswer->is_correct ? 'Correct' : 'Incorrect' }} · Points earned: {{ $existingAnswer->points_awarded }}</p>
            @else
                <form method="POST" action="{{ route('sessions.answer.store', $session) }}">
                    @csrf
                    <div class="option-grid">@foreach ($currentQuestion->options as $option)<label class="option-card"><input type="radio" name="question_option_id" value="{{ $option->id }}" required style="width:auto; margin-right: 8px;">{{ $option->option_text }}</label>@endforeach</div>
                    <div style="margin-top: 18px;"><button type="submit" class="button">Submit answer</button></div>
                </form>
            @endif
        </div>
    @endif

    <div class="card">
        <h2>Leaderboard</h2>
        @forelse ($leaderboard as $leader)
            <div style="padding: 10px 0; border-bottom: 1px solid #e2e8f0; display:flex; justify-content:space-between; gap:12px;">
                <strong>{{ $loop->iteration }}. {{ $leader->nickname }}</strong>
                <span>{{ $leader->score }} pts</span>
            </div>
        @empty
            <p class="muted">The leaderboard will appear once players join.</p>
        @endforelse
    </div>
@endsection
