<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Simulation Results</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $scenario->name }} · Run #{{ $run->id }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('reports.json', $run) }}" class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Download JSON</a>
                <a href="{{ route('reports.csv', $run) }}" class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Download CSV</a>
                <a href="{{ route('reports.pdf', $run) }}" class="inline-flex items-center rounded-lg bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-600">Download PDF</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800"><p class="text-sm text-gray-500 dark:text-gray-400">Company A Market Share</p><p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format((float) $finalResult->company_a_market_share * 100, 2) }}%</p></div>
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800"><p class="text-sm text-gray-500 dark:text-gray-400">Company B Market Share</p><p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format((float) $finalResult->company_b_market_share * 100, 2) }}%</p></div>
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800"><p class="text-sm text-gray-500 dark:text-gray-400">HHI</p><p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format((float) $finalResult->hhi, 4) }}</p></div>
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800"><p class="text-sm text-gray-500 dark:text-gray-400">Leader</p><p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $finalResult->leader_company }}</p></div>
            </div>

            @include('partials.charts.simulation-results-overview', [
                'results' => $results,
                'finalResult' => $finalResult,
                'run' => $run,
            ])

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Run Details by Period</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Seed: {{ $run->seed }} · Status: {{ ucfirst($run->status) }}</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/40">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Period</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">A Sales</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">B Sales</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">A Share</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">B Share</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">A Profit</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">B Profit</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">HHI</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Leader</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($results as $result)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $result->period }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $result->company_a_sales }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $result->company_b_sales }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ number_format((float) $result->company_a_market_share * 100, 2) }}%</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ number_format((float) $result->company_b_market_share * 100, 2) }}%</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ number_format((float) $result->company_a_profit, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ number_format((float) $result->company_b_profit, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ number_format((float) $result->hhi, 4) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $result->leader_company }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
