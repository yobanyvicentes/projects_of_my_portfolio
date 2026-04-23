<?php

namespace App\Services;

use App\Models\GameSession;

class GamePinService
{
    public function generateUniquePin(int $length = 6): string
    {
        do {
            $pin = str_pad((string) random_int(0, (10 ** $length) - 1), $length, '0', STR_PAD_LEFT);
        } while (GameSession::query()->where('pin', $pin)->exists());

        return $pin;
    }
}
