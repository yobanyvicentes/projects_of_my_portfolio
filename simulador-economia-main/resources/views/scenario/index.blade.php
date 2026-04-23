<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Scenarios
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Create, review, update, and run your simulation scenarios.
                </p>
            </div>

            <a href="{{ route('scenarios.create') }}" class="inline-flex items-center rounded-lg bg-slate-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-600">
                Create Scenario
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900/60 dark:bg-green-950/30 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/40">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Scenario</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Market</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Strategy</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Latest Result</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($scenarios as $scenario)
                                @php
                                    $latestCompletedRun = $scenario->runs
                                        ->where('status', 'completed')
                                        ->sortByDesc(fn ($run) => $run->executed_at ?? $run->created_at)
                                        ->first();
                                    $finalResult = $latestCompletedRun?->results->last();
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 align-top">
                                        <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $scenario->name }}</div>
                                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $scenario->consumers_count }} consumers · {{ $scenario->periods_count }} periods</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $scenario->market_type)) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $scenario->competitive_strategy)) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                        @if ($finalResult)
                                            Leader: {{ $finalResult->leader_company }}<br>
                                            HHI: {{ number_format((float) $finalResult->hhi, 4) }}
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">No completed run yet.</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap justify-end gap-2">
                                            <a href="{{ route('scenarios.show', $scenario) }}" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">View</a>
                                            <a href="{{ route('scenarios.edit', $scenario) }}" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Edit</a>
                                            <form method="POST" action="{{ route('scenarios.destroy', $scenario) }}" onsubmit="return confirm('Delete this scenario?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-lg border border-red-300 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-50 dark:border-red-800 dark:text-red-300 dark:hover:bg-red-950/30">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">No scenarios yet. Create your first scenario to begin.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{ $scenarios->links() }}
        </div>
    </div>
</x-app-layout>
