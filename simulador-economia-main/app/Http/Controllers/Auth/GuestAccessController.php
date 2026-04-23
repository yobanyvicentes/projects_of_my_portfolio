<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\GuestUserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class GuestAccessController extends Controller
{
    public function store(Request $request, GuestUserService $guestUserService): RedirectResponse
    {
        try {
            $guest = $guestUserService->create();
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->route('home')
                ->with('demo_error', 'Guest access could not be prepared right now. Please try again or register normally.');
        }

        Auth::login($guest);

        $request->session()->regenerate();

        return redirect()
            ->intended(RouteServiceProvider::HOME)
            ->with('guest_mode', 'Guest mode is ready. Two example scenarios and completed runs were prepared automatically.');
    }
}
