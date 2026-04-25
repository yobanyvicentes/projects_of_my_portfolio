<?php

namespace App\Services;

use App\Enums\CompetitiveStrategy;
use App\Enums\MarketType;
use App\Models\Scenario;
use App\Models\SimulationResult;
use App\Models\SimulationRun;
use Illuminate\Support\Facades\DB;

class SimulationService
{
    public function run(Scenario $scenario): SimulationRun
    {
        return DB::transaction(function () use ($scenario) {
            $seed = random_int(1000, 999999);

            $run = SimulationRun::create([
                'scenario_id' => $scenario->id,
                'user_id' => $scenario->user_id,
                'seed' => $seed,
                'status' => 'pending',
            ]);

            mt_srand($seed);

            $marketType = MarketType::from($scenario->market_type);
            $strategy = CompetitiveStrategy::from($scenario->competitive_strategy);
            $weights = $this->resolveWeights($marketType, $strategy);

            $periods = max((int) $scenario->periods_count, 1);
            $consumers = max((int) $scenario->consumers_count, 1);
            $priceA = max((float) $scenario->company_a_price, 0.01);
            $priceB = max((float) $scenario->company_b_price, 0.01);
            $adA = max((float) $scenario->company_a_ad_budget, 0);
            $adB = max((float) $scenario->company_b_ad_budget, 0);

            for ($period = 1; $period <= $periods; $period++) {
                $salesA = 0;
                $salesB = 0;
                $periodShock = $this->randomFloat(0.92, 1.08);

                for ($consumer = 1; $consumer <= $consumers; $consumer++) {
                    $consumerPriceWeight = $weights['price_weight'] * $this->randomFloat(0.75, 1.25);
                    $consumerAdWeight = $weights['ad_weight'] * $this->randomFloat(0.75, 1.25);

                    $utilityA = $this->consumerUtility(
                        $priceA,
                        $adA,
                        $consumerPriceWeight,
                        $consumerAdWeight,
                        $weights['noise_weight'],
                        $periodShock
                    );

                    $utilityB = $this->consumerUtility(
                        $priceB,
                        $adB,
                        $consumerPriceWeight,
                        $consumerAdWeight,
                        $weights['noise_weight'],
                        $periodShock
                    );

                    $probabilityA = $this->choiceProbability($utilityA, $utilityB);
                    $random = $this->randomFloat(0, 1);

                    if ($random <= $probabilityA) {
                        $salesA++;
                    } else {
                        $salesB++;
                    }
                }

                $totalSales = max($salesA + $salesB, 1);
                $marketShareA = $salesA / $totalSales;
                $marketShareB = $salesB / $totalSales;
                $profitA = ($priceA * $salesA) - $adA;
                $profitB = ($priceB * $salesB) - $adB;
                $hhi = round(($marketShareA ** 2) + ($marketShareB ** 2), 4);

                SimulationResult::create([
                    'simulation_run_id' => $run->id,
                    'period' => $period,
                    'company_a_sales' => $salesA,
                    'company_b_sales' => $salesB,
                    'company_a_market_share' => $marketShareA,
                    'company_b_market_share' => $marketShareB,
                    'company_a_profit' => $profitA,
                    'company_b_profit' => $profitB,
                    'hhi' => $hhi,
                    'leader_company' => $marketShareA >= $marketShareB ? 'Company A' : 'Company B',
                    'raw_data' => [
                        'market_type' => $marketType->value,
                        'competitive_strategy' => $strategy->value,
                        'weights' => $weights,
                        'price_a' => $priceA,
                        'price_b' => $priceB,
                        'ad_a' => $adA,
                        'ad_b' => $adB,
                        'period_shock' => round($periodShock, 4),
                    ],
                ]);
            }

            $run->update([
                'status' => 'completed',
                'executed_at' => now(),
                'notes' => sprintf(
                    'Run generated for %s using %s strategy.',
                    $marketType->label(),
                    $strategy->label()
                ),
            ]);

            return $run->fresh('results');
        });
    }

    protected function consumerUtility(
        float $price,
        float $advertisingBudget,
        float $priceWeight,
        float $adWeight,
        float $noiseWeight,
        float $periodShock
    ): float {
        $priceComponent = (120 / max($price, 0.01)) * $priceWeight;
        $advertisingComponent = log(max($advertisingBudget, 0) + 1) * 8 * $adWeight;
        $noise = $this->randomFloat(-$noiseWeight, $noiseWeight);

        return (($priceComponent + $advertisingComponent) * $periodShock) + $noise;
    }

    protected function choiceProbability(float $utilityA, float $utilityB): float
    {
        $delta = max(min($utilityA - $utilityB, 50), -50);

        return 1 / (1 + exp(-$delta / 8));
    }

    protected function resolveWeights(MarketType $marketType, CompetitiveStrategy $strategy): array
    {
        $base = match ($marketType) {
            MarketType::Duopoly => [
                'price_weight' => 1.00,
                'ad_weight' => 0.95,
                'noise_weight' => 1.10,
            ],
            MarketType::MonopolisticCompetition => [
                'price_weight' => 0.85,
                'ad_weight' => 1.20,
                'noise_weight' => 1.35,
            ],
            MarketType::PerfectCompetition => [
                'price_weight' => 1.35,
                'ad_weight' => 0.55,
                'noise_weight' => 0.80,
            ],
        };

        $modifier = match ($strategy) {
            CompetitiveStrategy::PriceCompetition => [
                'price_weight' => 1.25,
                'ad_weight' => 0.75,
                'noise_weight' => 0.95,
            ],
            CompetitiveStrategy::AdvertisingCompetition => [
                'price_weight' => 0.80,
                'ad_weight' => 1.35,
                'noise_weight' => 1.10,
            ],
            CompetitiveStrategy::Balanced => [
                'price_weight' => 1.00,
                'ad_weight' => 1.00,
                'noise_weight' => 1.00,
            ],
        };

        return [
            'price_weight' => round($base['price_weight'] * $modifier['price_weight'], 4),
            'ad_weight' => round($base['ad_weight'] * $modifier['ad_weight'], 4),
            'noise_weight' => round($base['noise_weight'] * $modifier['noise_weight'], 4),
        ];
    }

    protected function randomFloat(float $min, float $max): float
    {
        return $min + ((mt_rand() / mt_getrandmax()) * ($max - $min));
    }
}
