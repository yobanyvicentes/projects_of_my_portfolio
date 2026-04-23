@extends('layouts.game')

@section('title', 'Live session '.$session->pin)
@section('head')<meta http-equiv="refresh" content="4">@endsection

@section('content')
    @php
        $questionIds = $session->quiz->questions->pluck('id')->values();
        $index = $session->currentQuestion ? $questionIds->search($session->currentQuestion->id) : false;
        $currentQuestionIndex = $index === false ? 0 : $index + 1;
        $totalQuestions = $questionIds->count();
        $currentAnswersCount = $session->currentQuestion ? $session->answers->where('question_id', $session->currentQuestion->id)->count() : 0;
        $connectedPlayers = $session->players->filter(fn ($player) => $player->last_seen_at && $player->last_seen_at->diffInSeconds(now()) <= 15)->count();
    @endphp

    <div class="card">
        <span class="badge">{{ $session->status }}</span>
        <h1 style="margin-top: 12px;">Session PIN: {{ $session->pin }}</h1>
        <p class="muted">Share this PIN with players so they can join the session.</p>
        <p><strong>Join page:</strong> <a href="{{ route('sessions.join') }}">{{ route('sessions.join') }}</a></p>
        <p class="muted">This screen refreshes automatically every 4 seconds.</p>
        <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top: 16px;">
            <form method="POST" action="{{ route('sessions.start', $session) }}">@csrf<button type="submit" class="button success">Start game</button></form>
            <form method="POST" action="{{ route('sessions.next', $session) }}">@csrf<button type="submit" class="button warning">Next question / finish</button></form>
        </div>
    </div>

    <div class="grid grid-2">
        <div class="card"><h3>Total players</h3><p style="font-size: 32px; font-weight: 700; margin: 8px 0 0;">{{ $session->players->count() }}</p></div>
        <div class="card"><h3>Connected now</h3><p style="font-size: 32px; font-weight: 700; margin: 8px 0 0;">{{ $connectedPlayers }}</p></div>
        <div class="card"><h3>Question progress</h3><p style="font-size: 32px; font-weight: 700; margin: 8px 0 0;">{{ $currentQuestionIndex }}/{{ $totalQuestions }}</p></div>
        <div class="card"><h3>Answers received</h3><p style="font-size: 32px; font-weight: 700; margin: 8px 0 0;">{{ $currentAnswersCount }}</p></div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <h2>Current question</h2>
            @if ($session->currentQuestion)
                <p><strong>{{ $session->currentQuestion->prompt }}</strong></p>
                <p class="muted">Question {{ $currentQuestionIndex }} of {{ $totalQuestions }} · {{ $session->currentQuestion->time_limit_seconds }} seconds · {{ $session->currentQuestion->points }} points</p>
                <ul class="list">@foreach ($session->currentQuestion->options as $option)<li>{{ $option->option_text }} @if ($option->is_correct)<span class="badge">Right answer</span>@endif</li>@endforeach</ul>
            @else
                <p class="muted">No active question yet. Click start game to push the first question.</p>
            @endif
        </div>

        <div class="card">
            <h2>Players</h2>
            @forelse ($session->players->sortByDesc('score') as $player)
                @php $online = $player->last_seen_at && $player->last_seen_at->diffInSeconds(now()) <= 15; @endphp
                <div style="padding: 10px 0; border-bottom: 1px solid #e2e8f0; display:flex; justify-content:space-between; gap:12px; align-items:center;">
                    <div>
                        <strong>{{ $player->nickname }}</strong>
                        <div class="muted" style="font-size: 12px; margin-top: 4px;">{{ $online ? 'Connected' : 'Idle' }}</div>
                    </div>
                    <div style="text-align:right;"><span>{{ $player->score }} pts</span><div style="margin-top:4px;"><span class="badge">{{ $online ? 'Online' : 'Away' }}</span></div></div>
                </div>
            @empty
                <p class="muted">No players joined yet.</p>
            @endforelse
        </div>
    </div>
@endsection
