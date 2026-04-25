<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>FlatApp</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-black text-white antialiased">
    <main class="mx-auto max-w-6xl px-6 py-8">
        <header class="flex items-center justify-between border-b border-[#1F2937] pb-5">
            <a href="{{ route('home') }}" class="text-2xl font-black text-[#00D4FF]">FlatApp</a>
            <nav class="flex gap-3 text-sm">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-xl bg-[#00D4FF] px-4 py-2 font-bold text-black">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-xl border border-[#1F2937] px-4 py-2 font-semibold">Log in</a>
                    <a href="{{ route('register') }}" class="rounded-xl bg-[#00D4FF] px-4 py-2 font-bold text-black">Sign up</a>
                @endauth
            </nav>
        </header>

        <section class="grid items-center gap-10 py-16 lg:grid-cols-2">
            <div class="space-y-6">
                <p class="inline-flex rounded-full border border-[#00D4FF]/50 bg-[#00D4FF]/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.25em] text-[#00D4FF]">Mobile-first flatmate app</p>
                <h1 class="text-5xl font-black leading-tight sm:text-6xl">Run your flat without the awkward chats.</h1>
                <p class="max-w-2xl text-lg text-[#9CA3AF]">FlatApp helps flatmates manage chores, shopping, receipts, join requests, roles and activity history from one shared space.</p>
                <div class="flex flex-col gap-3 sm:flex-row">
                    @auth
                        <a href="{{ route('dashboard') }}" class="rounded-2xl bg-[#00D4FF] px-6 py-4 text-center font-bold text-black">Open dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="rounded-2xl bg-[#00D4FF] px-6 py-4 text-center font-bold text-black">Create account</a>
                        <a href="{{ route('login') }}" class="rounded-2xl border border-[#1F2937] px-6 py-4 text-center font-bold">Log in</a>
                    @endauth
                    <a href="{{ route('join.create') }}" class="rounded-2xl border border-[#00D4FF]/60 px-6 py-4 text-center font-bold text-[#00D4FF]">Join with code</a>
                </div>
            </div>

            <div class="rounded-[2rem] border border-[#1F2937] bg-[#05070A] p-5 shadow-2xl shadow-[#00D4FF]/10">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-[#9CA3AF]">Preview</p>
                        <h2 class="text-2xl font-bold">Wellington House</h2>
                    </div>
                    <span class="rounded-full bg-[#00D4FF] px-3 py-1 text-xs font-bold text-black">LIVE</span>
                </div>
                <div class="space-y-3">
                    <div class="rounded-2xl border border-[#1F2937] bg-black p-4"><div class="flex justify-between"><span>Kitchen clean-up</span><span class="rounded-full bg-yellow-400 px-2 py-1 text-xs font-bold text-black">pending</span></div><p class="mt-1 text-xs text-[#9CA3AF]">Assigned to Alex</p></div>
                    <div class="rounded-2xl border border-[#1F2937] bg-black p-4"><div class="flex justify-between"><span>Milk and coffee</span><span class="rounded-full bg-green-500 px-2 py-1 text-xs font-bold text-black">bought</span></div><p class="mt-1 text-xs text-[#9CA3AF]">Shopping list updated</p></div>
                    <div class="rounded-2xl border border-[#1F2937] bg-black p-4"><div class="flex justify-between"><span>Power bill uploaded</span><span class="rounded-full border border-[#00D4FF] px-2 py-1 text-xs font-bold text-[#00D4FF]">PDF</span></div><p class="mt-1 text-xs text-[#9CA3AF]">Visible to approved members</p></div>
                </div>
            </div>
        </section>

        <section class="grid gap-4 pb-12 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-3xl border border-[#1F2937] bg-[#0B0F14] p-5"><h3 class="font-bold">🏠 Flats</h3><p class="mt-2 text-sm text-[#9CA3AF]">Create flats and approve join requests.</p></div>
            <div class="rounded-3xl border border-[#1F2937] bg-[#0B0F14] p-5"><h3 class="font-bold">🧹 Chores</h3><p class="mt-2 text-sm text-[#9CA3AF]">Assign and track shared tasks.</p></div>
            <div class="rounded-3xl border border-[#1F2937] bg-[#0B0F14] p-5"><h3 class="font-bold">🛑 Shopping</h3><p class="mt-2 text-sm text-[#9CA3AF]">Manage shared groceries.</p></div>
            <div class="rounded-3xl border border-[#1F2937] g-[#0B0F14] p-5"><h3 class="font-bold">🧾 Receipts</h3><p class="mt-2 text-sm text-[#9CA3AF]">Upload bills and receipts.</p></div>
        </section>
    </main>
</body>
</html>
