<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Nailedit Live</title>
<style>
body{margin:0;font-family:Arial,sans-serif;background:#eef2ff;color:#111827}a{text-decoration:none}.hero{background:linear-gradient(135deg,#111827,#4f46e5);color:#fff;padding:64px 24px}.container{max-width:1000px;margin:0 auto}.button{display:inline-block;background:#fff;color:#312e81;padding:14px 18px;border-radius:14px;font-weight:700;margin:8px 12px 0 0}.button.alt{background:rgba(255,255,255,.12);color:#fff;border:1px solid rgba(255,255,255,.25)}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px;padding:28px 24px}.card{background:#fff;border-radius:18px;padding:24px;box-shadow:0 12px 30px rgba(15,23,42,.08)}
</style>
</head>
<body>
<section class="hero">
<div class="container">
<h1>Nailedit Live</h1>
<p>You need an account to create and manage quizzes. Players can still join live games without signing in.</p>
<a href="{{ route('login') }}" class="button">Login</a>
<a href="{{ route('register') }}" class="button alt">Register</a>
<a href="{{ route('sessions.join') }}" class="button alt">Join game</a>
</div>
</section>
<section class="grid container">
<div class="card"><h2>Host with an account</h2><p>Sign in to create quizzes, add questions, and manage live sessions.</p></div>
<div class="card"><h2>Launch</h2><p>Start a session and share the generated PIN.</p></div>
<div class="card"><h2>Play</h2><p>Players join, answer, and see the leaderboard.</p></div>
</section>

@include('layouts.footer')
</body>
</html>
