<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use App\Models\SessionPlayer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JoinGameController extends Controller
{
    public function create(): View
    {
        return view('sessions.join');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pin' => ['required', 'string', 'max:10'],
            'nickname' => ['required', 'string', 'max:255'],
        ]);

        $session = GameSession::query()
            ->where('pin', $validated['pin'])
            ->firstOrFail();

        abort_unless($session->status !== 'finished', 422, 'This game has already finished.');

        $player = SessionPlayer::query()->firstOrCreate(
            [
                'game_session_id' => $session->id,
                'nickname' => $validated['nickname'],
            ],
            [
                'joined_at' => now(),
                'last_seen_at' => now(),
                'status' => 'connected',
            ]
        );

        $player->forceFill([
            'last_seen_at' => now(),
            'status' => 'connected',
        ])->save();

        session([
            'active_game_session_id' => $session->id,
            'active_session_player_id' => $player->id,
        ]);

        return redirect()
            ->route('sessions.play', $session)
            ->with('status', 'You joined the game successfully.');
    }
}
