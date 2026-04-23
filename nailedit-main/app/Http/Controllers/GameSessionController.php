<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use App\Models\Quiz;
use App\Services\GamePinService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GameSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Quiz $quiz, GamePinService $pinService): RedirectResponse
    {
        abort_unless($quiz->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'status' => ['nullable', 'string', 'max:50'],
        ]);

        $session = GameSession::query()->create([
            'quiz_id' => $quiz->id,
            'host_user_id' => Auth::id(),
            'pin' => $pinService->generateUniquePin(),
            'status' => $validated['status'] ?? 'lobby',
        ]);

        return redirect()
            ->route('sessions.show', $session)
            ->with('status', 'Game session created successfully.');
    }

    public function show(GameSession $session): View
    {
        abort_unless($session->host_user_id === Auth::id(), 403);

        $session->load([
            'quiz.questions.options',
            'currentQuestion.options',
            'players',
            'answers',
        ]);

        return view('sessions.show', compact('session'));
    }
}
