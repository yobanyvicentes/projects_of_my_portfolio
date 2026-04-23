@include('partials.charts.chartjs-loader')

@php
    $resultPeriods = $results->pluck('period')->values();
    $salesA = $results->pluck('company_a_sales')->map(fn ($value) => (int) $value)->values();
    $salesB = $results->pluck('company_b_sales')->map(fn ($value) => (int) $value)->values();
    $shareA = $results->map(fn ($result) => round((float) $result->company_a_market_share * 100, 2))->values();
    $shareB = $results->map(fn ($result) => round((float) $result->company_b_market_share * 100, 2))->values();
    $profitA = $results->map(fn ($result) => round((float) $result->company_a_profit, 2))->values();
    $profitB = $results->map(fn ($result) => round((float) $result->company_b_profit, 2))->values();
    $hhiValues = $results->map(fn ($result) => round((float) $result->hhi, 4))->values();
@endphp

<section class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Charts by Period</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Visual comparison of sales, market share, profit and market concentration.</p>
    </div>

    <div class="grid gap-6 p-6 xl:grid-cols-2">
        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Sales by Period</h4>
            <div class="mt-4 h-80">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Market Share by Period</h4>
            <div class="mt-4 h-80">
                <canvas id="marketShareChart"></canvas>
            </div>
        </div>

        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Profit by Period</h4>
            <div class="mt-4 h-80">
                <canvas id="profitChart"></canvas>
            </div>
        </div>

        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">HHI by Period</h4>
            <div class="mt-4 h-80">
                <canvas id="hhiChart"></canvas>
            </div>
        </div>
    </div>
</section>

<script>
    (() => {
        if (!window.Chart) {
            return;
        }

        const periods = @json($resultPeriods);
        const salesA = @json($salesA);
        const salesB = @json($salesB);
        const shareA = @json($shareA);
        const shareB = @json($shareB);
        const profitA = @json($profitA);
        const profitB = @json($profitB);
        const hhiValues = @json($hhiValues);

        const buildLineDatasets = (labelA, dataA, labelB, dataB) => ([
            {
                label: labelA,
                data: dataA,
                tension: 0.3,
                borderWidth: 2,
            },
            {
                label: labelB,
                data: dataB,
                tension: 0.3,
                borderWidth: 2,
            }
        ]);

        const salesCanvas = document.getElementById('salesChart');
        const marketShareCanvas = document.getElementById('marketShareChart');
        const profitCanvas = document.getElementById('profitChart');
        const hhiCanvas = document.getElementById('hhiChart');

        if (salesCanvas) {
            new Chart(salesCanvas, {
                type: 'bar',
                data: {
                    labels: periods,
                    datasets: [
                        {
                            label: 'Company A Sales',
                            data: salesA,
                            borderWidth: 1,
                        },
                        {
                            label: 'Company B Sales',
                            data: salesB,
                            borderWidth: 1,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });
        }

        if (marketShareCanvas) {
            new Chart(marketShareCanvas, {
                type: 'line',
                data: {
                    labels: periods,
                    datasets: buildLineDatasets('Company A Share %', shareA, 'Company B Share %', shareB)
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

        if (profitCanvas) {
            new Chart(profitCanvas, {
                type: 'line',
                data: {
                    labels: periods,
                    datasets: buildLineDatasets('Company A Profit', profitA, 'Company B Profit', profitB)
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
                            beginAtZero: false,
                        }
                    }
                }
            });
        }

        if (hhiCanvas) {
            new Chart(hhiCanvas, {
                type: 'line',
                data: {
                    labels: periods,
                    datasets: [
                        {
                            label: 'HHI',
                            data: hhiValues,
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
