@extends('layouts.game')

@section('title', 'Join game')

@section('content')
    <div class="card" style="max-width: 640px; margin: 0 auto;">
        <h1>Join a live game</h1>
        <p class="muted">Enter the session PIN and choose a nickname.</p>

        <form method="POST" action="{{ route('sessions.join.store') }}">
            @csrf
            <label for="pin">Game PIN</label>
            <input id="pin" name="pin" type="text" value="{{ old('pin') }}" required>

            <label for="nickname">Nickname</label>
            <input id="nickname" name="nickname" type="text" value="{{ old('nickname') }}" required>

            <button type="submit" class="button">Join game</button>
        </form>
    </div>
@endsection
