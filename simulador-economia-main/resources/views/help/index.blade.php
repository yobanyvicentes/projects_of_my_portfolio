<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Help and Interpretation Guide
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Understand the scenario inputs, the simulation logic, and the formulas used to calculate results.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Scenario Inputs</h3>

                <ul class="mt-4 space-y-3 text-sm leading-6 text-gray-700 dark:text-gray-300">
                    <li><strong>Market Type:</strong> Defines the baseline weights for price, advertising, and randomness in consumer decisions.</li>
                    <li><strong>Competitive Strategy:</strong> Modifies how strongly price and advertising affect consumer choice.</li>
                    <li><strong>Prices:</strong> Selling price per unit for Company A and Company B.</li>
                    <li><strong>Advertising Budgets:</strong> Promotional spending used as part of the utility calculation.</li>
                    <li><strong>Consumers:</strong> Number of simulated purchase decisions per period.</li>
                    <li><strong>Periods:</strong> Number of iterations the simulation runs.</li>
                </ul>
            </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Mathematical Model Used in the Simulation</h3>
                <p class="mt-3 text-sm leading-6 text-gray-700 dark:text-gray-300">
                    For each period, the system simulates consumer choices between Company A and Company B using a utility-based probabilistic model.
                </p>

                <div class="mt-5 space-y-5">
                    <div>
                        <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">1. Consumer Utility</h4>
                        <p class="mt-2 text-sm leading-6 text-gray-700 dark:text-gray-300">
                            For each consumer and each company, utility is calculated from price, advertising, a period shock, and random noise.
                        </p>

                        <div class="mt-3 overflow-x-auto rounded-xl bg-gray-50 p-4 text-sm text-gray-800 dark:bg-gray-900/40 dark:text-gray-200">
<pre class="whitespace-pre-wrap">
price_component = (120 / max(price, 0.01)) * price_weight

advertising_component = log(max(ad_budget, 0) + 1) * 8 * ad_weight

utility = ((price_component + advertising_component) * period_shock) + noise
</pre>
                        </div>

                        <p class="mt-3 text-sm leading-6 text-gray-700 dark:text-gray-300">
                            Where:
                        </p>

                        <ul class="mt-2 list-disc space-y-2 pl-5 text-sm leading-6 text-gray-700 dark:text-gray-300">
                            <li><strong>period_shock</strong> is a random factor between <strong>0.92</strong> and <strong>1.08</strong>.</li>
                            <li><strong>noise</strong> is a random value between <strong>-noise_weight</strong> and <strong>+noise_weight</strong>.</li>
                            <li><strong>price_weight</strong>, <strong>ad_weight</strong>, and <strong>noise_weight</strong> depend on the selected market type and competitive strategy.</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">2. Choice Probability</h4>
                        <p class="mt-2 text-sm leading-6 text-gray-700 dark:text-gray-300">
                            After calculating utility for both firms, the model transforms the utility difference into a purchase probability using a logistic function.
                        </p>

                        <div class="mt-3 overflow-x-auto rounded-xl bg-gray-50 p-4 text-sm text-gray-800 dark:bg-gray-900/40 dark:text-gray-200">
<pre class="whitespace-pre-wrap">
delta = clamp(utility_a - utility_b, -50, 50)

P(A) = 1 / (1 + exp(-delta / 8))

P(B) = 1 - P(A)
</pre>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">3. Sales Formula</h4>
                        <p class="mt-2 text-sm leading-6 text-gray-700 dark:text-gray-300">
                            For each consumer, the simulator draws a random number between 0 and 1:
                        </p>

                        <div class="mt-3 overflow-x-auto rounded-xl bg-gray-50 p-4 text-sm text-gray-800 dark:bg-gray-900/40 dark:text-gray-200">
<pre class="whitespace-pre-wrap">
if random(0,1) <= P(A):
    sale goes to Company A
else:
    sale goes to Company B
</pre>
                        </div>

                        <p class="mt-3 text-sm leading-6 text-gray-700 dark:text-gray-300">
                            At the end of the period:
                        </p>

                        <div class="mt-3 overflow-x-auto rounded-xl bg-gray-50 p-4 text-sm text-gray-800 dark:bg-gray-900/40 dark:text-gray-200">
<pre class="whitespace-pre-wrap">
sales_a = number of consumers assigned to Company A
sales_b = number of consumers assigned to Company B
</pre>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">4. Market Share</h4>

                        <div class="mt-3 overflow-x-auto rounded-xl bg-gray-50 p-4 text-sm text-gray-800 dark:bg-gray-900/40 dark:text-gray-200">
<pre class="whitespace-pre-wrap">
market_share_a = sales_a / (sales_a + sales_b)

market_share_b = sales_b / (sales_a + sales_b)
</pre>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">5. Profit</h4>

                        <div class="mt-3 overflow-x-auto rounded-xl bg-gray-50 p-4 text-sm text-gray-800 dark:bg-gray-900/40 dark:text-gray-200">
<pre class="whitespace-pre-wrap">
profit_a = (price_a * sales_a) - ad_budget_a

profit_b = (price_b * sales_b) - ad_budget_b
</pre>
                        </div>

                        <p class="mt-3 text-sm leading-6 text-gray-700 dark:text-gray-300">
                            In this model, profit is revenue minus advertising cost for that period.
                        </p>
                    </div>

                    <div>
                        <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">6. HHI (Herfindahl-Hirschman Index)</h4>

                        <div class="mt-3 overflow-x-auto rounded-xl bg-gray-50 p-4 text-sm text-gray-800 dark:bg-gray-900/40 dark:text-gray-200">
<pre class="whitespace-pre-wrap">
HHI = (market_share_a^2) + (market_share_b^2)
</pre>
                        </div>

                        <p class="mt-3 text-sm leading-6 text-gray-700 dark:text-gray-300">
                            The stored value is rounded to 4 decimal places. Higher HHI values indicate a more concentrated market.
                        </p>
                    </div>

                    <div>
                        <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">7. Leader Criterion</h4>

                        <div class="mt-3 overflow-x-auto rounded-xl bg-gray-50 p-4 text-sm text-gray-800 dark:bg-gray-900/40 dark:text-gray-200">
<pre class="whitespace-pre-wrap">
if market_share_a >= market_share_b:
    leader = "Company A"
else:
    leader = "Company B"
</pre>
                        </div>

                        <p class="mt-3 text-sm leading-6 text-gray-700 dark:text-gray-300">
                            This means that in a tie, the system assigns leadership to <strong>Company A</strong>.
                        </p>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Weights by Market Type</h3>

                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                        <thead>
                            <tr class="text-left text-gray-600 dark:text-gray-300">
                                <th class="px-4 py-3 font-semibold">Market Type</th>
                                <th class="px-4 py-3 font-semibold">Price Weight</th>
                                <th class="px-4 py-3 font-semibold">Ad Weight</th>
                                <th class="px-4 py-3 font-semibold">Noise Weight</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-gray-700 dark:divide-gray-700 dark:text-gray-300">
                            <tr>
                                <td class="px-4 py-3">Duopoly</td>
                                <td class="px-4 py-3">1.00</td>
                                <td class="px-4 py-3">0.95</td>
                                <td class="px-4 py-3">1.10</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">Monopolistic Competition</td>
                                <td class="px-4 py-3">0.85</td>
                                <td class="px-4 py-3">1.20</td>
                                <td class="px-4 py-3">1.35</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">Perfect Competition</td>
                                <td class="px-4 py-3">1.35</td>
                                <td class="px-4 py-3">0.55</td>
                                <td class="px-4 py-3">0.80</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Strategy Modifiers</h3>

                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                        <thead>
                            <tr class="text-left text-gray-600 dark:text-gray-300">
                                <th class="px-4 py-3 font-semibold">Competitive Strategy</th>
                                <th class="px-4 py-3 font-semibold">Price Weight</th>
                                <th class="px-4 py-3 font-semibold">Ad Weight</th>
                                <th class="px-4 py-3 font-semibold">Noise Weight</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-gray-700 dark:divide-gray-700 dark:text-gray-300">
                            <tr>
                                <td class="px-4 py-3">Price Competition</td>
                                <td class="px-4 py-3">1.25</td>
                                <td class="px-4 py-3">0.75</td>
                                <td class="px-4 py-3">0.95</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">Advertising Competition</td>
                                <td class="px-4 py-3">0.80</td>
                                <td class="px-4 py-3">1.35</td>
                                <td class="px-4 py-3">1.10</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">Balanced Strategy</td>
                                <td class="px-4 py-3">1.00</td>
                                <td class="px-4 py-3">1.00</td>
                                <td class="px-4 py-3">1.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p class="mt-4 text-sm leading-6 text-gray-700 dark:text-gray-300">
                    The final weights used in the simulation are calculated by multiplying the market-type base weights by the selected strategy modifiers.
                </p>
            </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">How to Read the Results</h3>

                <ul class="mt-4 space-y-3 text-sm leading-6 text-gray-700 dark:text-gray-300">
                    <li><strong>Sales:</strong> Number of consumers assigned to each company in a period.</li>
                    <li><strong>Market Share:</strong> Percentage of total period sales captured by each firm.</li>
                    <li><strong>Profit:</strong> Revenue minus advertising budget in that period.</li>
                    <li><strong>Leader:</strong> Firm with the highest market share. Ties go to Company A.</li>
                    <li><strong>HHI:</strong> Concentration index computed from squared market shares.</li>
                </ul>
            </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recommended Workflow</h3>

                <ol class="mt-4 list-decimal space-y-3 pl-5 text-sm leading-6 text-gray-700 dark:text-gray-300">
                    <li>Create a scenario with the desired market type and strategy.</li>
                    <li>Define prices, advertising budgets, consumers, and periods.</li>
                    <li>Run the simulation.</li>
                    <li>Inspect period-by-period outputs and the final result.</li>
                    <li>Compare scenarios and export reports when needed.</li>
                </ol>
            </section>
        </div>
    </div>
</x-app-layout>
