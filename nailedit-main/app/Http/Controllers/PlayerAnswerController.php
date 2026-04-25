<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use App\Models\PlayerAnswer;
use Illuminate\Http\RedirectResponse;

class PlayerAnswerController extends Controller
{
    public function store(GameSession $session): RedirectResponse
    {
        abort_unless((int) session('active_game_session_id') === $session->id, 403);
        abort_unless($session->status === 'question_live', 422, 'Answers are closed right now.');

        $player = $session->players()->findOrFail(session('active_session_player_id'));
        $question = $session->currentQuestion;

        abort_unless($question, 422, 'No active question is available right now.');

        $data = request()->validate([
            'question_option_id' => ['required', 'integer'],
        ]);

        $existingAnswer = PlayerAnswer::query()
            ->where('game_session_id', $session->id)
            ->where('question_id', $question->id)
            ->where('session_player_id', $player->id)
            ->first();

        if ($existingAnswer) {
            return redirect()->route('sessions.play', $session)->with('status', 'You already answered this question.');
        }

        $option = $question->options()->findOrFail($data['question_option_id']);
        $isCorrect = (bool) $option->is_correct;
        $pointsAwarded = $isCorrect ? $question->points : 0;

        PlayerAnswer::query()->create([
            'game_session_id' => $session->id,
            'question_id' => $question->id,
            'session_player_id' => $player->id,
            'question_option_id' => $option->id,
            'is_correct' => $isCorrect,
            'points_awarded' => $pointsAwarded,
            'answered_at' => now(),
        ]);

        if ($pointsAwarded > 0) {
            $player->increment('score', $pointsAwarded);
        }

        return redirect()->route('sessions.play', $session)->with('status', $isCorrect ? 'Correct answer!' : 'Answer submitted.');
    }
}
