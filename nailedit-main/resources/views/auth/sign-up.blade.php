@extends('layouts.game')

@section('title', 'Register')

@section('content')
    <div class="card" style="max-width: 560px; margin: 0 auto;">
        <h1>Create account</h1>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <label for="name">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required>

            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>

            <label for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required>

            <button type="submit" class="button">Register</button>
        </form>
    </div>
@endsection
