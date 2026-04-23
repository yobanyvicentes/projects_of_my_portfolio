<?php

namespace App\Services\EconomicDashboard\Providers\WorldBank;

use Illuminate\Http\Client\Factory as HttpFactory;
use RuntimeException;

class WorldBankApiClient
{
    public function __construct(
        protected HttpFactory $http,
    ) {
    }

    public function fetchSeries(
        string $countryCode,
        string $indicatorCode,
        int $startYear,
        int $endYear,
    ): array {
        $config = config('economic-dashboard.providers.world_bank', []);

        $response = $this->http
            ->acceptJson()
            ->timeout((int) ($config['timeout_seconds'] ?? 12))
            ->retry((int) ($config['retry_times'] ?? 1), (int) ($config['retry_sleep_milliseconds'] ?? 200))
            ->get(rtrim((string) ($config['base_url'] ?? 'https://api.worldbank.org/v2'), '/')."/country/{$countryCode}/indicator/{$indicatorCode}", [
                'format' => 'json',
                'source' => (string) ($config['source_id'] ?? 2),
                'per_page' => (int) ($config['per_page'] ?? 2000),
                'date' => sprintf('%d:%d', $startYear, $endYear),
            ]);

        if (! $response->successful()) {
            throw new RuntimeException(sprintf(
                'World Bank API request failed with HTTP %d.',
                $response->status(),
            ));
        }

        $payload = $response->json();

        if (! is_array($payload) || count($payload) < 2) {
            throw new RuntimeException('World Bank API returned an unexpected payload structure.');
        }

        return [
            'pagination' => is_array($payload[0] ?? null) ? $payload[0] : [],
            'rows' => is_array($payload[1] ?? null) ? $payload[1] : [],
        ];
    }
}
