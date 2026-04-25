<?php

namespace App\Services;

use App\Enums\CompetitiveStrategy;
use App\Enums\MarketType;
use App\Models\Scenario;
use App\Models\User;
use Illuminate\Support\Collection;

class DemoScenarioProvisioner
{
    public function __construct(protected SimulationService $simulationService)
    {
    }

    public function provisionFor(User $user): Collection
    {
        return collect($this->definitions())
            ->map(function (array $definition) use ($user) {
                $scenario = Scenario::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'name' => $definition['name'],
                    ],
                    [
                        ...$definition,
                        'is_example' => true,
                    ]
                );

                if (! $scenario->runs()->where('status', 'completed')->exists()) {
                    $this->simulationService->run($scenario);
                }

                return $scenario->fresh(['runs.results']);
            });
    }

    protected function definitions(): array
    {
        return [
            [
                'name' => 'Example · Price Competition Stress Test',
                'market_type' => MarketType::Duopoly->value,
                'competitive_strategy' => CompetitiveStrategy::PriceCompetition->value,
                'company_a_price' => 17.90,
                'company_b_price' => 19.40,
                'company_a_ad_budget' => 1400,
                'company_b_ad_budget' => 1100,
                'consumers_count' => 650,
                'periods_count' => 8,
            ],
            [
                'name' => 'Example · Advertising Growth Playbook',
                'market_type' => MarketType::MonopolisticCompetition->value,
                'competitive_strategy' => CompetitiveStrategy::AdvertisingCompetition->value,
                'company_a_price' => 22.50,
                'company_b_price' => 21.90,
                'company_a_ad_budget' => 2600,
                'company_b_ad_budget' => 3400,
                'consumers_count' => 900,
                'periods_count' => 10,
            ],
        ];
    }
}
