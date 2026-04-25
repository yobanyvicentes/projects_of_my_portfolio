@include('partials.charts.chartjs-loader')

@php
    $chartPeriods = $results->pluck('period')->values();
    $chartSalesA = $results->pluck('company_a_sales')->map(fn ($value) => (int) $value)->values();
    $chartSalesB = $results->pluck('company_b_sales')->map(fn ($value) => (int) $value)->values();
    $chartShareA = $results->map(fn ($result) => round((float) $result->company_a_market_share * 100, 2))->values();
    $chartShareB = $results->map(fn ($result) => round((float) $result->company_b_market_share * 100, 2))->values();
    $chartProfitA = $results->map(fn ($result) => round((float) $result->company_a_profit, 2))->values();
    $chartProfitB = $results->map(fn ($result) => round((float) $result->company_b_profit, 2))->values();
    $chartFinalShareA = round((float) $finalResult->company_a_market_share * 100, 2);
    $chartFinalShareB = round((float) $finalResult->company_b_market_share * 100, 2);
    $chartSuffix = 'simulation-'.$run->id;
@endphp

<section class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Charts by period</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Visual comparison of sales, market share and profit for this run.</p>
    </div>

    <div class="grid gap-6 p-6 xl:grid-cols-2">
        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Sales by Period</h4>
            <div class="mt-4 h-80">
                <canvas id="salesChart-{{ $chartSuffix }}"></canvas>
            </div>
        </div>

        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Final Market Share</h4>
            <div class="mt-4 h-80">
                <canvas id="finalShareChart-{{ $chartSuffix }}"></canvas>
            </div>
        </div>

        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Market Share Trend</h4>
            <div class="mt-4 h-80">
                <canvas id="shareTrendChart-{{ $chartSuffix }}"></canvas>
            </div>
        </div>

        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Profit Trend</h4>
            <div class="mt-4 h-80">
                <canvas id="profitTrendChart-{{ $chartSuffix }}"></canvas>
            </div>
        </div>
    </div>
</section>

<script>
    (() => {
        if (!window.Chart) {
            return;
        }

        const periods = @json($chartPeriods);
        const salesA = @json($chartSalesA);
        const salesB = @json($chartSalesB);
        const shareA = @json($chartShareA);
        const shareB = @json($chartShareB);
        const profitA = @json($chartProfitA);
        const profitB = @json($chartProfitB);
        const finalShares = [@json($chartFinalShareA), @json($chartFinalShareB)];

        const salesCanvas = document.getElementById('salesChart-{{ $chartSuffix }}');
        const finalShareCanvas = document.getElementById('finalShareChart-{{ $chartSuffix }}');
        const shareTrendCanvas = document.getElementById('shareTrendChart-{{ $chartSuffix }}');
        const profitTrendCanvas = document.getElementById('profitTrendChart-{{ $chartSuffix }}');

        if (salesCanvas) {
            new Chart(salesCanvas, {
                type: 'bar',
                data: {
                    labels: periods,
                    datasets: [
                        {
                            label: 'Company A Sales',
                            data: salesA,
                            backgroundColor: '#2563eb',
                        },
                        {
                            label: 'Company B Sales',
                            data: salesB,
                            backgroundColor: '#f59e0b',
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                },
            });
        }

        if (finalShareCanvas) {
            new Chart(finalShareCanvas, {
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

        if (shareTrendCanvas) {
            new Chart(shareTrendCanvas, {
                type: 'line',
                data: {
                    labels: periods,
                    datasets: [
                        {
                            label: 'Company A Share %',
                            data: shareA,
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.15)',
                            tension: 0.3,
                            borderWidth: 2,
                        },
                        {
                            label: 'Company B Share %',
                            data: shareB,
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245, 158, 11, 0.15)',
                            tension: 0.3,
                            borderWidth: 2,
                        },
                    ],
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
                        },
                    },
                },
            });
        }

        if (profitTrendCanvas) {
            new Chart(profitTrendCanvas, {
                type: 'line',
                data: {
                    labels: periods,
                    datasets: [
                        {
                            label: 'Company A Profit',
                            data: profitA,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.15)',
                            tension: 0.3,
                            borderWidth: 2,
                        },
                        {
                            label: 'Company B Profit',
                            data: profitB,
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.15)',
                            tension: 0.3,
                            borderWidth: 2,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                },
            });
        }
    })();
</script>
