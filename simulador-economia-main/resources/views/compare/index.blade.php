<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Compare Scenarios</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Select two or three scenarios to compare their latest completed runs.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-red-800 dark:border-red-900/60 dark:bg-red-950/30 dark:text-red-200">
                    <ul class="list-disc space-y-1 pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <form method="POST" action="{{ route('compare.run') }}" class="space-y-6">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @forelse ($scenarios as $scenario)
                            <label class="flex items-start gap-3 rounded-xl border border-gray-200 p-4 transition hover:border-slate-400 dark:border-gray-700">
                                <input type="checkbox" name="selected_scenarios[]" value="{{ $scenario->id }}" class="mt-1 rounded border-gray-300 text-slate-700 focus:ring-slate-600" @checked(in_array($scenario->id, $selectedScenarioIds, true))>
                                <span>
                                    <span class="block font-semibold text-gray-900 dark:text-gray-100">{{ $scenario->name }}</span>
                                    <span class="block text-sm text-gray-500 dark:text-gray-400">{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $scenario->market_type)) }}</span>
                                </span>
                            </label>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">Create scenarios first before using the comparison module.</p>
                        @endforelse
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center rounded-lg bg-slate-700 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-slate-600">
                            Compare Selected Scenarios
                        </button>
                    </div>
                </form>
            </section>

            @if ($comparisons->isNotEmpty())
                <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Comparison Results</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/40">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Scenario</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Latest Run</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Leader</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">A Share</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">B Share</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">HHI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($comparisons as $comparison)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $comparison['scenario']->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                            @if ($comparison['run'])
                                                #{{ $comparison['run']->id }}
                                            @else
                                                No run
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $comparison['final_result']->leader_company ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $comparison['final_result'] ? number_format((float) $comparison['final_result']->company_a_market_share * 100, 2) . '%' : 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $comparison['final_result'] ? number_format((float) $comparison['final_result']->company_b_market_share * 100, 2) . '%' : 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $comparison['final_result'] ? number_format((float) $comparison['final_result']->hhi, 4) : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-app-layout>
