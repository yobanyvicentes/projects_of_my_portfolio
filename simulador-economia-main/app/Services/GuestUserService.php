<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Throwable;

class GuestUserService
{
    public function __construct(protected DemoScenarioProvisioner $demoScenarioProvisioner)
    {
    }

    public function create(): User
    {
        $guest = User::create([
            'name' => 'Guest Reviewer '.Str::upper(Str::random(4)),
            'email' => 'guest+'.Str::ulid().'@economy-simulator.local',
            'password' => Hash::make(Str::random(40)),
            'is_guest' => true,
        ]);

        try {
            $this->demoScenarioProvisioner->provisionFor($guest);

            return $guest->fresh();
        } catch (Throwable $e) {
            $this->delete($guest);

            throw $e;
        }
    }

    public function delete(User $user): void
    {
        if (! $user->isGuest()) {
            return;
        }

        $runs = $user->simulationRuns()->with('results')->get();

        foreach ($runs as $run) {
            $run->results()->delete();
        }

        $user->simulationRuns()->delete();
        $user->scenarios()->delete();
        $user->delete();
    }
}
