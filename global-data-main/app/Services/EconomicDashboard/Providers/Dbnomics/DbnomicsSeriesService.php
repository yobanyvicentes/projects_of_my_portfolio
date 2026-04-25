<?php

namespace App\Services\EconomicDashboard\Providers\Dbnomics;

use App\Services\EconomicDashboard\Providers\AbstractProviderSeriesService;

class DbnomicsSeriesService extends AbstractProviderSeriesService
{
    public function __construct(
        protected DbnomicsApiClient $client,
    ) {
    }

    protected function fetchSeries(array $filters, array $indicator): array
    {
        $seriesId = sprintf(
            '%s/%s/%s%s%s',
            $this->providerConfig()['provider_code'] ?? 'WB',
            $this->providerConfig()['dataset_code'] ?? 'WDI',
            $this->providerConfig()['annual_series_prefix'] ?? 'A-',
            $indicator['code'],
            ($this->providerConfig()['country_separator'] ?? '-').$filters['country'],
        );

        return collect($this->client->fetchSeries($seriesId))
            ->filter(function (array $row) use ($filters) {
                $year = $this->rowYear($row);

                return $year !== null
                    && $year >= (int) $filters['startYear']
                    && $year <= (int) $filters['endYear'];
            })
            ->values()
            ->all();
    }

    protected function normalizeRecord(array $row, array $filters, array $indicator, string $countryName): ?array
    {
        $year = $this->rowYear($row);
        $value = is_numeric($row['value'] ?? null) ? (float) $row['value'] : null;

        if ($year === null) {
            return null;
        }

        return [
            'source' => $this->sourceLabel(),
            'source_indicator_code' => $indicator['code'],
            'source_indicator_name' => $row['series_name'] ?? $indicator['source_name'],
            'normalized_indicator_key' => $filters['indicator'],
            'normalized_indicator_label' => $indicator['label'],
            'country_code' => $filters['country'],
            'country_name' => $row['Country'] ?? $row['country_name'] ?? $countryName,
            'year' => $year,
            'value' => $value,
            'formatted_value' => $value !== null ? $this->formatValue($value, $indicator) : 'N/A',
            'unit' => $row['Unit'] ?? $indicator['unit'],
            'notes' => $indicator['is_proxy']
                ? 'DBnomics is returning the closest mapped fiscal debt proxy from the source dataset.'
                : 'DBnomics annual observation.',
            'metadata' => [
                'provider_code' => $row['provider_code'] ?? null,
                'dataset_code' => $row['dataset_code'] ?? null,
                'dataset_name' => $row['dataset_name'] ?? null,
                'series_code' => $row['series_code'] ?? null,
                'series_name' => $row['series_name'] ?? null,
                'indexed_at' => $row['indexed_at'] ?? null,
                'frequency' => $row['@frequency'] ?? null,
                'source_note' => $indicator['note'] ?? null,
            ],
        ];
    }

    protected function providerConfigKey(): string
    {
        return 'dbnomics';
    }

    protected function providerCachePrefix(): string
    {
        return 'economic-dashboard:dbnomics';
    }

    protected function sourceLabel(): string
    {
        return 'DBnomics';
    }

    protected function emptyStateMessage(): string
    {
        return 'This can happen when the mapped series is unavailable through DBnomics for the selected country or the requested years.';
    }

    protected function providerMethodologyNote(): string
    {
        return 'DBnomics republishes public data as-is, preserves original dataset and series codes, and standardizes access through a unified API.';
    }

    protected function providerErrorTitle(): string
    {
        return 'DBnomics data is temporarily unavailable';
    }

    protected function additionalMethodologyNotes(array $indicator, array $records, array $filters): array
    {
        return [
            'The current DBnomics integration uses the World Bank WDI dataset through DBnomics, so source attribution remains visible even though access is routed through the DBnomics API.',
        ];
    }

    protected function rowYear(array $row): ?int
    {
        $originalPeriod = (string) ($row['original_period'] ?? '');

        if (preg_match('/^(\d{4})$/', $originalPeriod, $matches) === 1) {
            return (int) $matches[1];
        }

        $period = (string) ($row['period'] ?? '');

        if (preg_match('/^(\d{4})/', $period, $matches) === 1) {
            return (int) $matches[1];
        }

        return null;
    }
}
