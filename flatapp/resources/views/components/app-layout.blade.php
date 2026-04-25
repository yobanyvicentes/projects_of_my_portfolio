<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name', 'FlatApp') }}</title>
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-black text-white min-h-screen">
<div class="max-w-md mx-auto">
<header class="p-4 border-b border-[#1F2937]">
<form method="POST" action="{{ route('current-flat.switch') }}">
@csrf
<select name="flat_id" onchange="this.form.submit()" class="w-full bg-[#0B0F14] border border-[#1F2937] rounded-xl p-2">
@foreach(auth()->user()?->memberships ?? [] as $m)
<option value="{{ $m->flat_id }}" @selected($currentFlat?->id === $m->flat_id)>{{ $m->flat->name }}</option>
@endforeach
</select>
</form>
</header>
<main class="p-4">{{ $slot }}</main>
</div>
</body>
</html>