<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_registered_users_receive_demo_scenarios_with_completed_runs(): void
    {
        $response = $this->post('/register', [
            'name' => 'Yobany Tester',
            'email' => 'yobany@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $user = User::where('email', 'yobany@example.com')->firstOrFail();

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
        $this->assertFalse($user->isGuest());

        $this->assertSame(2, $user->scenarios()->where('is_example', true)->count());
        $this->assertSame(2, $user->simulationRuns()->where('status', 'completed')->count());

        $this->assertTrue(
            $user->scenarios()
                ->where('is_example', true)
                ->withCount('runs')
                ->get()
                ->every(fn ($scenario) => $scenario->runs_count >= 1)
        );
    }

    public function test_guest_access_creates_a_temporary_user_with_demo_scenarios(): void
    {
        $response = $this->post(route('guest.access'));

        $user = User::latest('id')->firstOrFail();

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
        $this->assertTrue($user->isGuest());
        $this->assertSame(2, $user->scenarios()->where('is_example', true)->count());
        $this->assertSame(2, $user->simulationRuns()->where('status', 'completed')->count());
    }

    public function test_logging_out_removes_guest_accounts(): void
    {
        $this->post(route('guest.access'));

        $user = User::latest('id')->firstOrFail();

        $this->assertAuthenticatedAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
