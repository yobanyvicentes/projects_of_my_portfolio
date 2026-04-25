<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Academic Insights | Colfuturo Data</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
</head>
<body class="bg-slate-100 text-slate-900">
    @php
        $yearMin = $filters['promotion_year']['min'] ?? null;
        $yearMax = $filters['promotion_year']['max'] ?? null;
        $yearFilterAvailable = (bool) ($filters['promotion_year']['available'] ?? false);

        $yearOptions = $yearFilterAvailable
            ? range((int) $yearMin, (int) $yearMax)
            : [];

        $activeYearMin = $yearFilterAvailable
            ? max((int) ($activeFilters['promotion_year_min'] ?? $yearMin), (int) $yearMin)
            : null;

        $activeYearMax = $yearFilterAvailable
            ? min((int) ($activeFilters['promotion_year_max'] ?? $yearMax), (int) $yearMax)
            : null;
    @endphp

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8" x-data="page(@js($dashboard), @js($filters))">
        <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">COLFUTURO Data</p>
                <h1 class="mt-2 text-4xl font-bold tracking-tight">Academic Insights</h1>
                <p class="mt-3 max-w-3xl text-sm text-slate-600">
                    This app explores records of selected applicants and beneficiaries from COLFUTURO’s Scholarship Loan Program:
                    a Colombian initiative that guides and funds postgraduate studies abroad.
                </p>
            </div>
            <a href="{{ route('home') }}" class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">Home</a>
        </div>

        <section class="mb-8 rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold">What is this database?</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">
                COLFUTURO guides and funds Colombian professionals to pursue master’s degrees, doctorates, and some
                eligible postgraduate programs abroad. The Scholarship Loan Program supports full-time, in-person
                programs, usually between 9 and 24 months. This visualization helps you analyze patterns by promotion year,
                gender, areas, universities, and destinations.
            </p>
        </section>

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-6">
            <div class="rounded-2xl bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Profiles</p><p class="mt-2 text-3xl font-bold" x-text="dashboard.kpis.total_profiles"></p></div>
            <div class="rounded-2xl bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Beneficiaries</p><p class="mt-2 text-3xl font-bold" x-text="dashboard.kpis.beneficiaries"></p></div>
            <div class="rounded-2xl bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Selected</p><p class="mt-2 text-3xl font-bold" x-text="dashboard.kpis.selected"></p></div>
            <div class="rounded-2xl bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Countries</p><p class="mt-2 text-3xl font-bold" x-text="dashboard.kpis.countries"></p></div>
            <div class="rounded-2xl bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Universities</p><p class="mt-2 text-3xl font-bold" x-text="dashboard.kpis.postgraduate_universities"></p></div>
            <div class="rounded-2xl bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Gender</p><p class="mt-2 text-sm text-slate-700"><span class="font-semibold" x-text="dashboard.kpis.male_total"></span> male · <span class="font-semibold" x-text="dashboard.kpis.female_total"></span> female</p></div>
        </section>

        <section class="mt-8 rounded-2xl bg-white p-6 shadow-sm">
            <div class="mb-4">
                <h2 class="text-lg font-semibold">Filters</h2>
                <p class="text-sm text-slate-500">Charts compare total vs male vs female after filters are applied.</p>
            </div>

            <form method="GET" class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="xl:col-span-2">
                    <label class="mb-1 block text-sm font-medium">General search</label>
                    <input type="text" name="search" value="{{ $activeFilters['search'] ?? '' }}" placeholder="Country, university, program, city..." class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Area</label>
                    <select name="area" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <option value="">All</option>
                        @foreach ($filters['areas'] as $option)
                            <option value="{{ $option }}" @selected(($activeFilters['area'] ?? '') === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Postgraduate type</label>
                    <select name="postgraduate_type" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <option value="">All</option>
                        @foreach ($filters['postgraduate_types'] as $option)
                            <option value="{{ $option }}" @selected(($activeFilters['postgraduate_type'] ?? '') === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($yearFilterAvailable)
                    <div class="xl:col-span-2 rounded-2xl border border-slate-200 p-4" x-data="{ minYear: '{{ $activeYearMin }}', maxYear: '{{ $activeYearMax }}' }">
                        <div class="flex items-center justify-between gap-3">
                            <label class="block text-sm font-medium">Promotion / year</label>
                            <span class="text-sm text-slate-600"><span x-text="minYear"></span> - <span x-text="maxYear"></span></span>
                        </div>

                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-slate-500">From</label>
                                <select
                                    name="promotion_year_min"
                                    x-model="minYear"
                                    @change="if (Number(minYear) > Number(maxYear)) maxYear = minYear"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                                    @foreach ($yearOptions as $option)
                                        <option value="{{ $option }}" :disabled="Number({{ $option }}) > Number(maxYear)" @selected($activeYearMin === $option)>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-slate-500">To</label>
                                <select
                                    name="promotion_year_max"
                                    x-model="maxYear"
                                    @change="if (Number(maxYear) < Number(minYear)) minYear = maxYear"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                                    @foreach ($yearOptions as $option)
                                        <option value="{{ $option }}" :disabled="Number({{ $option }}) < Number(minYear)" @selected($activeYearMax === $option)>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-2 flex justify-between text-xs text-slate-500">
                            <span>{{ $yearMin }}</span>
                            <span>{{ $yearMax }}</span>
                        </div>
                    </div>
                @else
                    <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-medium">Promotion / year</label>
                            <span class="text-sm text-slate-500">Unavailable</span>
                        </div>
                        <p class="mt-2 text-sm text-slate-500">
                            No valid promotion years are available in the current dataset yet. Re-importing the CSV after this update will populate the year filter when the source file includes a valid promotion-year column.
                        </p>
                    </div>
                @endif

                <div>
                    <label class="mb-1 block text-sm font-medium">Destination country</label>
                    <select name="country" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <option value="">All</option>
                        @foreach ($filters['countries'] as $option)
                            <option value="{{ $option }}" @selected(($activeFilters['country'] ?? '') === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Department</label>
                    <select name="department" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <option value="">All</option>
                        @foreach ($filters['departments'] as $option)
                            <option value="{{ $option }}" @selected(($activeFilters['department'] ?? '') === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="xl:col-span-4 flex flex-wrap gap-3">
                    <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Apply filters</button>
                    <a href="{{ route('home') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Reset</a>
                </div>
            </form>
        </section>

        <section class="mt-8 grid gap-6 xl:grid-cols-2">
            <div class="rounded-2xl bg-white p-6 shadow-sm"><h2 class="mb-4 text-lg font-semibold">Top 10 destination countries</h2><div class="h-[420px]"><canvas id="countriesChart"></canvas></div></div>
            <div class="rounded-2xl bg-white p-6 shadow-sm"><h2 class="mb-4 text-lg font-semibold">Top 10 postgraduate universities</h2><div class="h-[420px]"><canvas id="universitiesChart"></canvas></div></div>
            <div class="rounded-2xl bg-white p-6 shadow-sm"><h2 class="mb-4 text-lg font-semibold">Top 10 areas</h2><div class="h-[420px]"><canvas id="areasChart"></canvas></div></div>
            <div class="rounded-2xl bg-white p-6 shadow-sm"><h2 class="mb-4 text-lg font-semibold">Top 10 origin departments</h2><div class="h-[420px]"><canvas id="departmentsChart"></canvas></div></div>
        </section>

        <section class="mt-8 grid gap-6 xl:grid-cols-2">
            <div class="rounded-2xl bg-white p-6 shadow-sm"><h2 class="text-lg font-semibold">Origin map</h2><div id="originMap" class="mt-4 h-80 rounded-xl"></div></div>
            <div class="rounded-2xl bg-white p-6 shadow-sm"><h2 class="text-lg font-semibold">Destination map</h2><div id="destinationMap" class="mt-4 h-80 rounded-xl"></div></div>
        </section>

        <section class="mt-8 grid gap-6 xl:grid-cols-5">
            <div class="xl:col-span-3 rounded-2xl bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">Filtered profiles</h2>
                        <p class="text-sm text-slate-500">Desktop keeps the full table. Mobile switches to compact cards for easier reading.</p>
                    </div>
                </div>

                <div class="space-y-4 md:hidden">
                    @forelse ($profiles as $profile)
                        <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="truncate text-base font-semibold text-slate-900">{{ $profile->name }}</h3>
                                    <p class="mt-1 text-xs text-slate-500">Promotion year: {{ $profile->promotion_year ?: '—' }}</p>
                                </div>
                                <span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">{{ $profile->status ?: '—' }}</span>
                            </div>

                            <dl class="mt-4 space-y-2 text-sm text-slate-600">
                                <div>
                                    <dt class="font-medium text-slate-800">Origin</dt>
                                    <dd>{{ $profile->department ?: '—' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-slate-800">Undergraduate university</dt>
                                    <dd>{{ $profile->undergraduate_university ?: '—' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-slate-800">Destination</dt>
                                    <dd>{{ $profile->country ?: '—' }}{{ $profile->destination_city ? ' · ' . $profile->destination_city : '' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-slate-800">Program</dt>
                                    <dd>{{ $profile->postgraduate_type ?: '—' }}{{ $profile->postgraduate_program ? ' · ' . $profile->postgraduate_program : '' }}</dd>
                                </div>
                            </dl>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center text-slate-500">
                            No profiles were found for the selected filters.
                        </div>
                    @endforelse
                </div>

                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead><tr class="text-left text-slate-500"><th class="pb-3 pr-4">Year</th><th class="pb-3 pr-4">Name</th><th class="pb-3 pr-4">Origin</th><th class="pb-3 pr-4">Destination</th><th class="pb-3 pr-4">Program</th><th class="pb-3">Status</th></tr></thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($profiles as $profile)
                                <tr><td class="py-3 pr-4 text-slate-600">{{ $profile->promotion_year }}</td><td class="py-3 pr-4 font-medium">{{ $profile->name }}</td><td class="py-3 pr-4 text-slate-600">{{ $profile->department }} · {{ $profile->undergraduate_university }}</td><td class="py-3 pr-4 text-slate-600">{{ $profile->country }} · {{ $profile->destination_city }}</td><td class="py-3 pr-4 text-slate-600">{{ $profile->postgraduate_type }} · {{ $profile->postgraduate_program }}</td><td class="py-3 text-slate-600">{{ $profile->status }}</td></tr>
                            @empty
                                <tr><td colspan="6" class="py-8 text-center text-slate-500">No profiles were found for the selected filters.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $profiles->links() }}</div>
            </div>

            <div class="xl:col-span-2 rounded-2xl bg-white p-6 shadow-sm" x-data="recommender(@js($filters))">
                <div class="mb-4"><h2 class="text-lg font-semibold">Recommender</h2><p class="text-sm text-slate-500">Recommendations are based only on postgraduate type and area.</p></div>
                <div class="space-y-6">
                    <div class="rounded-2xl border border-slate-200 p-4">
                        <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Study intention</h3>
                        <p class="mt-2 text-sm text-slate-500">Choose a postgraduate type, an area, or both to find similar study outcomes.</p>
                        <div class="mt-4 grid gap-4">
                            <div><label class="mb-1 block text-sm font-medium">Postgraduate type</label><select x-model="form.postgraduate_type" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm"><option value="">Any</option><template x-for="option in filters.postgraduate_types || []" :key="option"><option :value="option" x-text="option"></option></template></select></div>
                            <div><label class="mb-1 block text-sm font-medium">Area</label><select x-model="form.area" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm"><option value="">Any</option><template x-for="option in filters.areas || []" :key="option"><option :value="option" x-text="option"></option></template></select></div>
                        </div>
                    </div>

                    <button @click="submit()" type="button" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Get recommendations</button>
                    <div x-show="loading" class="text-sm text-slate-500">Calculating recommendations...</div>
                    <template x-if="result"><div class="space-y-5 pt-2"><div><h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Top countries</h3><ul class="mt-2 space-y-2"><template x-for="item in result.recommended_countries" :key="item.label"><li class="flex justify-between rounded-xl bg-slate-50 px-3 py-2 text-sm"><span x-text="item.label"></span><span x-text="item.total"></span></li></template></ul></div><div><h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Top universities</h3><ul class="mt-2 space-y-2"><template x-for="item in result.recommended_universities" :key="item.label"><li class="flex justify-between rounded-xl bg-slate-50 px-3 py-2 text-sm"><span x-text="item.label"></span><span x-text="item.total"></span></li></template></ul></div><div><h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Why these suggestions?</h3><ul class="mt-2 list-disc space-y-2 pl-5 text-sm text-slate-700"><template x-for="message in result.explanations" :key="message"><li x-text="message"></li></template></ul></div></div></template>
                </div>
            </div>
        </section>

        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
                <div class="text-center text-sm sm:text-left">&nbsp;</div>
                <div class="flex flex-col items-center text-sm text-gray-500 sm:flex-row sm:justify-end sm:ml-0">
                    <div class="flex items-center space-x-1">
                        <select class="bg-transparent border-none p-0 text-sm text-gray-500 focus:ring-0 cursor-pointer hover:text-gray-700"><option value="designed">Designed by</option><option value="developed">Developed by</option><option value="built">Built by</option><option value="created">Created by</option><option value="powered">Powered by</option><option value="made">Made with ❤️ by</option></select>
                        <span>Yobany Vicentes - All rights reserved &copy; {{ date('Y') }}</span>
                    </div>
                    <a href="{{ route('home') }}" class="ml-1 underline hover:text-gray-700 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Colfuturo Data</a>
                </div>
            </div>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function page(dashboard, filters) {
            return {
                dashboard,
                filters,
                init() {
                    this.renderComparisonChart('countriesChart', this.dashboard.top_10_countries);
                    this.renderComparisonChart('universitiesChart', this.dashboard.top_10_universities);
                    this.renderComparisonChart('areasChart', this.dashboard.top_10_areas);
                    this.renderComparisonChart('departmentsChart', this.dashboard.top_10_departments);
                    this.renderMap('originMap', this.dashboard.origin_map, [4.5709, -74.2973], 5);
                    this.renderMap('destinationMap', this.dashboard.destination_map, [20, 0], 2);
                },
                renderComparisonChart(id, items) {
                    const element = document.getElementById(id);
                    if (!element || !items?.length) return;

                    new Chart(element, {
                        type: 'bar',
                        data: {
                            labels: items.map(item => item.label),
                            datasets: [
                                {
                                    label: 'Total',
                                    data: items.map(item => item.total),
                                    backgroundColor: 'rgba(148, 163, 184, 0.8)',
                                    borderColor: 'rgb(100, 116, 139)',
                                    borderWidth: 1,
                                },
                                {
                                    label: 'Male',
                                    data: items.map(item => item.male_total),
                                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                                    borderColor: 'rgb(37, 99, 235)',
                                    borderWidth: 1,
                                },
                                {
                                    label: 'Female',
                                    data: items.map(item => item.female_total),
                                    backgroundColor: 'rgba(236, 72, 153, 0.8)',
                                    borderColor: 'rgb(219, 39, 119)',
                                    borderWidth: 1,
                                },
                            ],
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: { x: { beginAtZero: true } },
                        },
                    });
                },
                renderMap(id, items, center, zoom) {
                    const element = document.getElementById(id);
                    if (!element) return;

                    const map = L.map(id).setView(center, zoom);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    (items || []).forEach(item => {
                        L.circleMarker([item.lat, item.lng], {
                            radius: Math.max(6, Math.min(20, item.total / 2))
                        }).addTo(map).bindPopup(`<strong>${item.name}</strong><br>Total: ${item.total}`);
                    });
                }
            }
        }

        function recommender(filters) {
            return {
                loading: false,
                result: null,
                filters,
                form: { postgraduate_type: '', area: '' },
                async submit() {
                    this.loading = true;

                    const response = await fetch('{{ route('academic-insights.recommend') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(this.form),
                    });

                    this.result = await response.json();
                    this.loading = false;
                }
            }
        }
    </script>
</body>
</html>
