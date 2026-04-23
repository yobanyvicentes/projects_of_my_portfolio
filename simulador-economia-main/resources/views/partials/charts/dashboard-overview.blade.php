@include('partials.charts.chartjs-loader')

@php
    $dashboardPeriods = $latestRun->results->pluck('period')->values();
    $dashboardHhi = $latestRun->results->map(fn ($result) => round((float) $result->hhi, 4))->values();
    $dashboardFinalShareA = round((float) $latestRunSummary->company_a_market_share * 100, 2);
    $dashboardFinalShareB = round((float) $latestRunSummary->company_b_market_share * 100, 2);
    $dashboardChartSuffix = 'run-'.$latestRun->id;
@endphp

<section class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Visual overview</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Quick charts for the latest completed run.</p>
    </div>

    <div class="grid gap-6 p-6 lg:grid-cols-2">
        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Final Market Share</h4>
            <div class="mt-4 h-72">
                <canvas id="dashboardShareChart-{{ $dashboardChartSuffix }}"></canvas>
            </div>
        </div>

        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">HHI Trend by Period</h4>
            <div class="mt-4 h-72">
                <canvas id="dashboardHhiChart-{{ $dashboardChartSuffix }}"></canvas>
            </div>
        </div>
    </div>
</section>

<script>
    (() => {
        if (!window.Chart) {
            return;
        }

        const shareCanvas = document.getElementById('dashboardShareChart-{{ $dashboardChartSuffix }}');
        const hhiCanvas = document.getElementById('dashboardHhiChart-{{ $dashboardChartSuffix }}');
        const periods = @json($dashboardPeriods);
        const hhiValues = @json($dashboardHhi);
        const finalShares = [@json($dashboardFinalShareA), @json($dashboardFinalShareB)];

        if (shareCanvas) {
            new Chart(shareCanvas, {
                type: 'doughnut',
                data: {
                    labels: ['Company A', 'Company B'],
                    datasets: [{
                        data: finalShares,
                        backgroundColor: ['#2563eb', '#f59e0b'],
                        borderWidth: 0,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                    },
                },
            });
        }

        if (hhiCanvas) {
            new Chart(hhiCanvas, {
                type: 'line',
                data: {
                    labels: periods,
                    datasets: [{
                        label: 'HHI',
                        data: hhiValues,
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.15)',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 2,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                },
            });
        }
    })();
</script>
