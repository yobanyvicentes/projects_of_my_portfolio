@include('partials.charts.chartjs-loader')

@php
    $dashboardPeriods = $latestRun->results->pluck('period')->values();
    $dashboardShareA = $latestRun->results->map(fn ($result) => round((float) $result->company_a_market_share * 100, 2))->values();
    $dashboardShareB = $latestRun->results->map(fn ($result) => round((float) $result->company_b_market_share * 100, 2))->values();
    $dashboardHhi = $latestRun->results->map(fn ($result) => round((float) $result->hhi, 4))->values();
@endphp

<section class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Latest run trends</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Quick visual summary of market share and concentration across periods.</p>
    </div>

    <div class="grid gap-6 p-6 lg:grid-cols-2">
        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Market Share by Period</h4>
            <div class="mt-4 h-80">
                <canvas id="dashboardMarketShareChart"></canvas>
            </div>
        </div>

        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">HHI Trend</h4>
            <div class="mt-4 h-80">
                <canvas id="dashboardHhiChart"></canvas>
            </div>
        </div>
    </div>
</section>

<script>
    (() => {
        const periods = @json($dashboardPeriods);
        const shareA = @json($dashboardShareA);
        const shareB = @json($dashboardShareB);
        const hhi = @json($dashboardHhi);

        const marketShareCanvas = document.getElementById('dashboardMarketShareChart');
        const hhiCanvas = document.getElementById('dashboardHhiChart');

        if (marketShareCanvas && window.Chart) {
            new Chart(marketShareCanvas, {
                type: 'line',
                data: {
                    labels: periods,
                    datasets: [
                        {
                            label: 'Company A Share %',
                            data: shareA,
                            tension: 0.3,
                            borderWidth: 2,
                        },
                        {
                            label: 'Company B Share %',
                            data: shareB,
                            tension: 0.3,
                            borderWidth: 2,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                        }
                    }
                }
            });
        }

        if (hhiCanvas && window.Chart) {
            new Chart(hhiCanvas, {
                type: 'line',
                data: {
                    labels: periods,
                    datasets: [
                        {
                            label: 'HHI',
                            data: hhi,
                            tension: 0.3,
                            borderWidth: 2,
                            fill: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                        }
                    }
                }
            });
        }
    })();
</script>
