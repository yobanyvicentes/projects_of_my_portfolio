@extends('layouts.game')

@section('title', 'Log in')

@section('content')
    <div class="card" style="max-width: 560px; margin: 0 auto;">
        <h1>Log in to host quizzes</h1>
        <p class="muted">Use your account to create quizzes, add questions, and manage live sessions.</p>

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>

            <label>
                <input type="checkbox" name="remember" value="1" style="width:auto; margin-right: 8px;"> Remember me
            </label>

            <button type="submit" class="button">Log in</button>
        </form>

        <p class="muted" style="margin-top: 16px;">New here? <a href="{{ route('register') }}">Create an account</a>.</p>
    </div>
@endsection
