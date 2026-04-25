<?php

namespace App\Policies;

use App\Models\GameSession;
use App\Models\User;

class HostSessionPolicy
{
    public function view(User $user, GameSession $session): bool
    {
        return $session->host_user_id === $user->id;
    }

    public function update(User $user, GameSession $session): bool
    {
        return $session->host_user_id === $user->id;
    }
}
