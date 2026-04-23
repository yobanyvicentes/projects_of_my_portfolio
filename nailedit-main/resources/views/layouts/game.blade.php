<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Nailedit Live')</title>
    @yield('head')
    <style>
        :root { color-scheme: light; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, Helvetica, sans-serif; background: #f4f7fb; color: #18212f; }
        a { color: #4f46e5; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .nav { background: #111827; color: #fff; padding: 16px 24px; }
        .nav .inner, .container { max-width: 1100px; margin: 0 auto; }
        .nav a { color: #fff; margin-right: 16px; font-weight: 600; }
        .container { padding: 24px; }
        .card { background: #fff; border-radius: 16px; padding: 20px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); margin-bottom: 20px; }
        .grid { display: grid; gap: 20px; }
        .grid-2 { grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }
        h1, h2, h3 { margin-top: 0; }
        .button { display: inline-block; border: none; border-radius: 12px; background: #4f46e5; color: white; padding: 12px 16px; font-weight: 700; cursor: pointer; }
        .button.secondary { background: #0f172a; }
        .button.success { background: #059669; }
        .button.warning { background: #d97706; }
        .button:disabled { opacity: 0.6; cursor: not-allowed; }
        input, textarea, select { width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 12px; margin-top: 6px; margin-bottom: 16px; font: inherit; }
        label { font-weight: 700; display: block; }
        .muted { color: #64748b; }
        .badge { display: inline-block; background: #e0e7ff; color: #312e81; padding: 6px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
        .status { background: #dcfce7; color: #166534; border-radius: 12px; padding: 14px 16px; margin-bottom: 20px; }
        .errors { background: #fef2f2; color: #991b1b; border-radius: 12px; padding: 14px 16px; margin-bottom: 20px; }
        .list { margin: 0; padding-left: 18px; }
        .option-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; }
        .option-card { border: 1px solid #dbeafe; border-radius: 14px; padding: 14px; background: #f8fbff; }
        .hero { padding: 48px 24px; background: linear-gradient(135deg, #111827, #4338ca); color: white; }
        .hero h1 { font-size: 42px; margin-bottom: 10px; }
        .hero p { max-width: 760px; font-size: 18px; line-height: 1.6; color: #dbeafe; }
    </style>
</head>
<body>
    <div class="nav">
        <div class="inner">
            <a href="{{ url('/') }}">Nailedit Live</a>
            <a href="{{ route('quizzes.index') }}">Host dashboard</a>
            <a href="{{ route('quizzes.create') }}">Create quiz</a>
            <a href="{{ route('sessions.join') }}">Join game</a>

            @guest
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endguest

            @auth
                <span style="margin-right: 16px; font-weight: 600;">{{ auth()->user()->name }}</span>
                <a href="{{ route('logout') }}">Logout</a>
            @endauth
        </div>
    </div>

    <div class="container">
        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="errors">
                <strong>Please fix the following:</strong>
                <ul class="list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    @include('layouts.footer')

    @yield('scripts')
</body>
</html>
