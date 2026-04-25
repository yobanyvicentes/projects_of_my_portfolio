<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\DemoScenarioProvisioner;
use Illuminate\Auth\Events\Registered;
use Throwable;

class ProvisionDemoScenariosForRegisteredUser
{
    public function __construct(protected DemoScenarioProvisioner $demoScenarioProvisioner)
    {
    }

    public function handle(Registered $event): void
    {
        if (! $event->user instanceof User || $event->user->isGuest()) {
            return;
        }

        try {
            $this->demoScenarioProvisioner->provisionFor($event->user);
        } catch (Throwable $e) {
            report($e);
        }
    }
}
