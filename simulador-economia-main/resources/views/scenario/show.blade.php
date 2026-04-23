<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">{{ $scenario->name }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Review the scenario inputs and manage its simulation runs.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('scenarios.edit', $scenario) }}" class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Edit Scenario</a>
                <a href="{{ route('scenarios.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Back to Scenarios</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900/60 dark:bg-green-950/30 dark:text-green-200">{{ session('success') }}</div>
            @endif

            @if (session('warning'))
                <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-900/60 dark:bg-amber-950/30 dark:text-amber-200">{{ session('warning') }}</div>
            @endif

            <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Scenario Inputs</h3>
                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40"><p class="text-sm text-gray-500 dark:text-gray-400">Market Type</p><p class="mt-2 font-semibold text-gray-900 dark:text-gray-100">{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $scenario->market_type)) }}</p></div>
                        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40"><p class="text-sm text-gray-500 dark:text-gray-400">Competitive Strategy</p><p class="mt-2 font-semibold text-gray-900 dark:text-gray-100">{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $scenario->competitive_strategy)) }}</p></div>
                        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40"><p class="text-sm text-gray-500 dark:text-gray-400">Company A Price</p><p class="mt-2 font-semibold text-gray-900 dark:text-gray-100">{{ number_format((float) $scenario->company_a_price, 2) }}</p></div>
                        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40"><p class="text-sm text-gray-500 dark:text-gray-400">Company B Price</p><p class="mt-2 font-semibold text-gray-900 dark:text-gray-100">{{ number_format((float) $scenario->company_b_price, 2) }}</p></div>
                        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40"><p class="text-sm text-gray-500 dark:text-gray-400">Company A Advertising Budget</p><p class="mt-2 font-semibold text-gray-900 dark:text-gray-100">{{ number_format((float) $scenario->company_a_ad_budget, 2) }}</p></div>
                        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40"><p class="text-sm text-gray-500 dark:text-gray-400">Company B Advertising Budget</p><p class="mt-2 font-semibold text-gray-900 dark:text-gray-100">{{ number_format((float) $scenario->company_b_ad_budget, 2) }}</p></div>
                        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40"><p class="text-sm text-gray-500 dark:text-gray-400">Consumers</p><p class="mt-2 font-semibold text-gray-900 dark:text-gray-100">{{ $scenario->consumers_count }}</p></div>
                        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40"><p class="text-sm text-gray-500 dark:text-gray-400">Periods</p><p class="mt-2 font-semibold text-gray-900 dark:text-gray-100">{{ $scenario->periods_count }}</p></div>
                    </div>
                </section>

                <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Actions</h3>
                    <div class="mt-6 space-y-4">
                        <form method="POST" action="{{ route('simulations.run', $scenario) }}">
                            @csrf
                            <button type="submit" class="w-full rounded-lg bg-slate-700 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-slate-600">Run Simulation</button>
                        </form>

                        <form method="POST" action="{{ route('simulations.reset', $scenario) }}" onsubmit="return confirm('Delete all runs for this scenario?');">
                            @csrf
                            <button type="submit" class="w-full rounded-lg border border-red-300 px-4 py-3 text-sm font-semibold text-red-700 hover:bg-red-50 dark:border-red-800 dark:text-red-300 dark:hover:bg-red-950/30">Reset Scenario Runs</button>
                        </form>

                        @if ($latestCompletedRun)
                            <a href="{{ route('simulations.results', $latestCompletedRun) }}" class="inline-flex w-full items-center justify-center rounded-lg border border-gray-300 px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">View Latest Results</a>
                        @endif
                    </div>

                    @if ($latestCompletedResult)
                        <div class="mt-6 rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Latest Summary</p>
                            <ul class="mt-3 space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <li><strong>Leader:</strong> {{ $latestCompletedResult->leader_company }}</li>
                                <li><strong>HHI:</strong> {{ number_format((float) $latestCompletedResult->hhi, 4) }}</li>
                                <li><strong>Company A Share:</strong> {{ number_format((float) $latestCompletedResult->company_a_market_share * 100, 2) }}%</li>
                                <li><strong>Company B Share:</strong> {{ number_format((float) $latestCompletedResult->company_b_market_share * 100, 2) }}%</li>
                            </ul>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
