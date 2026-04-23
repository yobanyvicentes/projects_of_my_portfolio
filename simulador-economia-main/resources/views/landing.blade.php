<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Economy Simulator</title>

        <meta name="description" content="Create economic scenarios, compare outcomes, and explore the simulator instantly with a guest demo account.">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-100 font-sans text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">
        <div class="min-h-screen flex flex-col">
            <header class="border-b border-gray-200 bg-white/90 backdrop-blur dark:border-gray-800 dark:bg-gray-900/90">
                <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3">
                        <img src="{{ url('images/logo-yobany.PNG') }}" alt="Yobany Vicentes logo" class="h-12 w-auto sm:h-14" />
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">Economy Simulator</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Interactive market scenario analysis for recruiters, reviewers and end users.</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-end gap-3">
                        @auth
                            <a
                                href="{{ route('dashboard') }}"
                                class="inline-flex items-center rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700"
                            >
                                Go to dashboard
                            </a>

                            <a
                                href="{{ route('profile.edit') }}"
                                class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800"
                            >
                                Profile
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800"
                                >
                                    Log out
                                </button>
                            </form>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800"
                            >
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a
                                    href="{{ route('register') }}"
                                    class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800"
                                >
                                    Register
                                </a>
                            @endif

                            <form method="POST" action="{{ route('guest.access') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="inline-flex items-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-500"
                                >
                                    Continue as guest
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            </header>

            <main class="flex-1">
                <section class="mx-auto max-w-7xl px-4 pt-8 sm:px-6 lg:px-8">
                    @if (session('demo_error'))
                        <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700 shadow-sm dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-200">
                            {{ session('demo_error') }}
                        </div>
                    @endif
                </section>

                <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                    <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
                        <div>
                            <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">
                                Demo-ready application
                            </span>

                            <h1 class="mt-6 max-w-4xl text-4xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-5xl">
                                Explore the full Economy Simulator workflow in minutes.
                            </h1>

                            <p class="mt-6 max-w-3xl text-lg leading-8 text-slate-600 dark:text-slate-300">
                                Create and compare market scenarios, run simulations, and review sales, market share, profit and HHI results.
                                Recruiters and reviewers can also enter instantly through guest mode with ready-to-use demo scenarios.
                            </p>

                            <div class="mt-8 flex flex-wrap gap-4">
                                @guest
                                    <form method="POST" action="{{ route('guest.access') }}">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="inline-flex items-center rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200"
                                        >
                                            Try guest mode now
                                        </button>
                                    </form>

                                    <a
                                        href="{{ route('register') }}"
                                        class="inline-flex items-center rounded-xl border border-gray-300 px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800"
                                    >
                                        Create an account
                                    </a>
                                @else
                                    <a
                                        href="{{ route('dashboard') }}"
                                        class="inline-flex items-center rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200"
                                    >
                                        Open dashboard
                                    </a>
                                @endguest
                            </div>

                            <dl class="mt-10 grid gap-4 sm:grid-cols-3">
                                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                                    <dt class="text-sm font-semibold text-slate-900 dark:text-white">2 demo scenarios</dt>
                                    <dd class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">
                                        Automatically provisioned for new users and guest sessions.
                                    </dd>
                                </div>

                                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                                    <dt class="text-sm font-semibold text-slate-900 dark:text-white">Completed simulation runs</dt>
                                    <dd class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">
                                        Users can inspect the dashboard, results pages and reports immediately.
                                    </dd>
                                </div>

                                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                                    <dt class="text-sm font-semibold text-slate-900 dark:text-white">Guest-friendly review flow</dt>
                                    <dd class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">
                                        Perfect for recruiters who want to see the system working without registration friction.
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-xl dark:border-gray-800 dark:bg-gray-900">
                            <h2 class="text-xl font-semibold text-slate-900 dark:text-white">What the guest demo includes</h2>

                            <div class="mt-6 space-y-4">
                                <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/60">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">Example 1 · Price Competition Stress Test</p>
                                    <p class="mt-1 text-sm leading-6 text-slate-600 dark:text-slate-300">
                                        A duopoly scenario that emphasizes pricing pressure and immediate share shifts across multiple periods.
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/60">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">Example 2 · Advertising Growth Playbook</p>
                                    <p class="mt-1 text-sm leading-6 text-slate-600 dark:text-slate-300">
                                        A scenario centered on advertising intensity and market positioning to showcase contrasting competitive outcomes.
                                    </p>
                                </div>

                                <div class="rounded-2xl border border-dashed border-amber-300 bg-amber-50 p-4 dark:border-amber-500/40 dark:bg-amber-500/10">
                                    <p class="text-sm font-semibold text-amber-900 dark:text-amber-100">Temporary guest session</p>
                                    <p class="mt-1 text-sm leading-6 text-amber-800 dark:text-amber-200">
                                        Guest access creates a temporary account automatically. It is intended for evaluation and can be removed on logout, while registered users keep their own data permanently.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>

            @include('layouts.footer')
        </div>
    </body>
</html>
