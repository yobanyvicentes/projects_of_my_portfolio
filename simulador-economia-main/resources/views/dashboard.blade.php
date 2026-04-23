<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Overview
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Review your latest activity and jump back into the simulation workflow.
            </p>
        </div>
    </x-slot>

    @php
        $hasLatestRun = $latestRun && $latestRun->results && $latestRun->results->isNotEmpty();
        $hasRecentScenarios = $recentScenarios && $recentScenarios->count() > 0;
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">
            @if (auth()->user()?->isGuest())
                <section class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm dark:border-amber-500/30 dark:bg-amber-500/10">
                    <h3 class="text-base font-semibold text-amber-900 dark:text-amber-100">
                        Guest mode is active
                    </h3>
                    <p class="mt-1 text-sm text-amber-800 dark:text-amber-200">
                        You are exploring the app with a temporary guest account that already includes two example scenarios and completed simulation runs.
                        Guest accounts are removed automatically after logout. If you want to keep your work permanently, log out and create a normal account from the home page.
                    </p>

                    @if (session('guest_mode'))
                        <p class="mt-3 text-sm font-medium text-amber-900 dark:text-amber-100">
                            {{ session('guest_mode') }}
                        </p>
                    @endif
                </section>
            @endif

            <div class="grid gap-6 md:grid-cols-3">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Scenarios</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ $scenarioCount }}
                    </p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Completed Runs</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ $completedRunsCount }}
                    </p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Quick Actions</p>
                    <div class="mt-3 flex flex-wrap gap-3">
                        <a
                            href="{{ route('scenarios.create') }}"
                            class="inline-flex items-center rounded-lg bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-600"
                        >
                            New Scenario
                        </a>

                        <a
                            href="{{ route('compare.index') }}"
                            class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700"
                        >
                            Compare
                        </a>
                    </div>
                </div>
            </div>

            @if ($hasLatestRun)
                @php
                    $latestRunSummary = $latestRun->results->last();
                @endphp

                <section class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Latest completed run
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $latestRun->scenario->name }} · {{ optional($latestRun->executed_at)->format('Y-m-d H:i') }}
                        </p>
                    </div>

                    <div class="grid gap-4 p-6 md:grid-cols-4">
                        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Company A Market Share</p>
                            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ number_format((float) $latestRunSummary->company_a_market_share * 100, 2) }}%
                            </p>
                        </div>

                        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Company B Market Share</p>
                            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ number_format((float) $latestRunSummary->company_b_market_share * 100, 2) }}%
                            </p>
                        </div>

                        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
                            <p class="text-sm text-gray-500 dark:text-gray-400">HHI</p>
                            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ number_format((float) $latestRunSummary->hhi, 4) }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Leader</p>
                            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ $latestRunSummary->leader_company }}
                            </p>
                        </div>
                    </div>
                </section>

                @include('partials.charts.dashboard-overview', [
                    'latestRun' => $latestRun,
                    'latestRunSummary' => $latestRunSummary,
                ])
            @endif

            <section class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Recent Scenarios
                    </h3>
                </div>

                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @if ($hasRecentScenarios)
                        @foreach ($recentScenarios as $scenario)
                            @php
                                $scenarioLatestRun = $scenario->runs
                                    ->where('status', 'completed')
                                    ->sortByDesc(fn ($run) => $run->executed_at ?? $run->created_at)
                                    ->first();

                                $scenarioFinalResult = $scenarioLatestRun?->results->last();
                            @endphp

                            <div class="flex flex-col gap-3 px-6 py-4 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $scenario->name }}
                                    </p>

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $scenario->market_type)) }}
                                        ·
                                        {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $scenario->competitive_strategy)) }}
                                    </p>

                                    @if ($scenarioFinalResult)
                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                            Latest leader: {{ $scenarioFinalResult->leader_company }} ·
                                            HHI {{ number_format((float) $scenarioFinalResult->hhi, 4) }}
                                        </p>
                                    @endif
                                </div>

                                <div class="flex flex-wrap gap-3">
                                    <a
                                        href="{{ route('scenarios.show', $scenario) }}"
                                        class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700"
                                    >
                                        View Scenario
                                    </a>

                                    @if ($scenarioLatestRun)
                                        <a
                                            href="{{ route('simulations.results', $scenarioLatestRun) }}"
                                            class="inline-flex items-center rounded-lg bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-600"
                                        >
                                            View Results
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if (! $hasRecentScenarios)
                        <div class="px-6 py-8 text-sm text-gray-500 dark:text-gray-400">
                            No scenarios yet. Create your first scenario to start the workflow.
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
