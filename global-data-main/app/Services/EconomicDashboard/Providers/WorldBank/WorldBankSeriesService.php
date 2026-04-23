<?php

namespace App\Services\EconomicDashboard\Providers\WorldBank;

use App\Services\EconomicDashboard\Providers\AbstractProviderSeriesService;
use Illuminate\Support\Arr;

class WorldBankSeriesService extends AbstractProviderSeriesService
{
    public function __construct(
        protected WorldBankApiClient $client,
    ) {
    }

    protected function fetchSeries(array $filters, array $indicator): array
    {
        $payload = $this->client->fetchSeries(
            (string) $filters['country'],
            (string) $indicator['code'],
            (int) $filters['startYear'],
            (int) $filters['endYear'],
        );

        return collect($payload['rows'] ?? [])
            ->filter(fn ($row) => is_array($row) && is_numeric($row['date'] ?? null))
            ->values()
            ->all();
    }

    protected function normalizeRecord(array $row, array $filters, array $indicator, string $countryName): ?array
    {
        $value = is_numeric($row['value'] ?? null) ? (float) $row['value'] : null;

        return [
            'source' => $this->sourceLabel(),
            'source_indicator_code' => $indicator['code'],
            'source_indicator_name' => Arr::get($row, 'indicator.value', $indicator['source_name']),
            'normalized_indicator_key' => $filters['indicator'],
            'normalized_indicator_label' => $indicator['label'],
            'country_code' => $filters['country'],
            'country_name' => Arr::get($row, 'country.value', $countryName),
            'year' => (int) $row['date'],
            'value' => $value,
            'formatted_value' => $value !== null ? $this->formatValue($value, $indicator) : 'N/A',
            'unit' => $indicator['unit'],
            'notes' => $indicator['is_proxy']
                ? 'World Bank proxy used for the fiscal debt requirement.'
                : 'World Bank annual observation.',
            'metadata' => [
                'decimal' => $row['decimal'] ?? null,
                'obs_status' => $row['obs_status'] ?? null,
                'raw_unit' => $row['unit'] ?? null,
                'source_note' => $indicator['note'] ?? null,
            ],
        ];
    }

    protected function providerConfigKey(): string
    {
        return 'world_bank';
    }

    protected function providerCachePrefix(): string
    {
        return 'economic-dashboard:world-bank';
    }

    protected function sourceLabel(): string
    {
        return 'World Bank';
    }

    protected function emptyStateMessage(): string
    {
        return 'This can happen when the selected country, indicator or recent years do not have published values in the World Development Indicators dataset.';
    }

    protected function providerMethodologyNote(): string
    {
        return 'World Bank Indicators API v2 is queried from the country/indicator endpoint using annual JSON responses, and World Development Indicators can have missing observations for specific countries or years.';
    }

    protected function providerErrorTitle(): string
    {
        return 'World Bank data is temporarily unavailable';
    }
}
