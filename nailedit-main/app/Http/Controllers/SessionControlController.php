<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class SessionControlController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function start(GameSession $session): RedirectResponse
    {
        abort_unless($session->host_user_id === Auth::id(), 403);

        $firstQuestionId = $session->quiz->questions()->orderBy('sort_order')->value('id');

        abort_unless($firstQuestionId, 422, 'Add at least one question before starting the game.');

        $session->forceFill([
            'current_question_id' => $firstQuestionId,
            'status' => 'question_live',
            'started_at' => $session->started_at ?? now(),
        ])->save();

        return redirect()
            ->route('sessions.show', $session)
            ->with('status', 'Game started.');
    }

    public function next(GameSession $session): RedirectResponse
    {
        abort_unless($session->host_user_id === Auth::id(), 403);

        $questionIds = $session->quiz->questions()
            ->orderBy('sort_order')
            ->pluck('id')
            ->values();

        if ($questionIds->isEmpty()) {
            return redirect()
                ->route('sessions.show', $session)
                ->with('status', 'This quiz has no questions yet.');
        }

        $currentIndex = $questionIds->search($session->current_question_id);
        $nextQuestionId = $currentIndex === false
            ? $questionIds->first()
            : $questionIds->get($currentIndex + 1);

        if ($nextQuestionId) {
            $session->forceFill([
                'current_question_id' => $nextQuestionId,
                'status' => 'question_live',
            ])->save();

            return redirect()
                ->route('sessions.show', $session)
                ->with('status', 'Moved to the next question.');
        }

        $session->forceFill([
            'status' => 'finished',
            'ended_at' => now(),
        ])->save();

        return redirect()
            ->route('sessions.show', $session)
            ->with('status', 'Game finished.');
    }
}
