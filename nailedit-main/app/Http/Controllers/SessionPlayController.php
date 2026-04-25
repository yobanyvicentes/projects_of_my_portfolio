<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use Illuminate\View\View;

class SessionPlayController extends Controller
{
    public function show(GameSession $session): View
    {
        abort_unless((int) session('active_game_session_id') === $session->id, 403);
        $player = $session->players()->findOrFail(session('active_session_player_id'));
        $player->last_seen_at = now();
        $player->status = 'connected';
        $player->save();

        $session->load([
            'currentQuestion.options',
            'players' => fn ($query) => $query->orderByDesc('score')->orderBy('nickname'),
        ]);

        $currentQuestion = $session->currentQuestion;
        $existingAnswer = $currentQuestion ? $player->answers()->where('question_id', $currentQuestion->id)->first() : null;
        $leaderboard = $session->players->take(10);

        return view('sessions.play', compact('session', 'player', 'currentQuestion', 'existingAnswer', 'leaderboard'));
    }
}
