<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SignupController extends Controller
{
    public function create(): View
    {
        return view('auth.sign-up');
    }

    public function ping(): string
    {
        return 'ok';
    }
}
