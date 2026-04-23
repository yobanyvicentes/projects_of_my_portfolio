<?php

namespace App\Services\EconomicDashboard\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Throwable;

abstract class AbstractProviderSeriesService
{
    public function query(array $filters): array
    {
        $indicator = $this->indicatorConfig((string) $filters['indicator']);
        $countryName = $this->countryName((string) $filters['country']);
        $cacheKey = $this->cacheKey($filters, (string) $indicator['code']);

        try {
            $rawRows = Cache::remember(
                $cacheKey,
                now()->addSeconds((int) ($this->providerConfig()['cache_ttl_seconds'] ?? 21600)),
                fn () => $this->fetchSeries($filters, $indicator),
            );

            return $this->successResult($filters, $indicator, $countryName, $rawRows, $cacheKey);
        } catch (Throwable $exception) {
            report($exception);

            return [
                'queryState' => 'error',
                'records' => [],
                'chartPoints' => [],
                'summaryCards' => $this->summaryCards($countryName, $indicator['label'], [], $filters),
                'warning' => $this->providerWarning($indicator, [], $filters),
                'error' => [
                    'title' => $this->providerErrorTitle(),
                    'message' => 'The query could not be completed right now. Please try again with the same filters or adjust the year range.',
                ],
                'emptyState' => null,
                'methodologyNotes' => $this->methodologyNotes($indicator, [], $filters),
                'meta' => [
                    'countryName' => $countryName,
                    'indicatorLabel' => $indicator['label'],
                    'cacheKey' => $cacheKey,
                    'requestedRange' => sprintf('%d–%d', $filters['startYear'], $filters['endYear']),
                ],
            ];
        }
    }

    protected function successResult(array $filters, array $indicator, string $countryName, array $rawRows, string $cacheKey): array
    {
        $records = collect($rawRows)
            ->map(fn (array $row) => $this->normalizeRecord($row, $filters, $indicator, $countryName))
            ->filter(fn ($row) => is_array($row) && $row['value'] !== null)
            ->sortBy('year')
            ->values()
            ->all();

        if ($records === []) {
            return [
                'queryState' => 'empty',
                'records' => [],
                'chartPoints' => [],
                'summaryCards' => $this->summaryCards($countryName, $indicator['label'], [], $filters),
                'warning' => $this->providerWarning($indicator, [], $filters),
                'error' => null,
                'emptyState' => [
                    'title' => sprintf('No %s observations were returned', $this->sourceLabel()),
                    'message' => $this->emptyStateMessage(),
                ],
                'methodologyNotes' => $this->methodologyNotes($indicator, [], $filters),
                'meta' => [
                    'countryName' => $countryName,
                    'indicatorLabel' => $indicator['label'],
                    'cacheKey' => $cacheKey,
                    'requestedRange' => sprintf('%d–%d', $filters['startYear'], $filters['endYear']),
                ],
            ];
        }

        return [
            'queryState' => 'success',
            'records' => $records,
            'chartPoints' => collect($records)
                ->map(fn (array $record) => [
                    'year' => $record['year'],
                    'value' => $record['value'],
                    'formattedValue' => $record['formatted_value'],
                ])
                ->values()
                ->all(),
            'summaryCards' => $this->summaryCards($countryName, $indicator['label'], $records, $filters),
            'warning' => $this->providerWarning($indicator, $records, $filters),
            'error' => null,
            'emptyState' => null,
            'methodologyNotes' => $this->methodologyNotes($indicator, $records, $filters),
            'meta' => [
                'countryName' => $countryName,
                'indicatorLabel' => $indicator['label'],
                'cacheKey' => $cacheKey,
                'requestedRange' => sprintf('%d–%d', $filters['startYear'], $filters['endYear']),
                'latestYear' => Arr::last($records)['year'] ?? null,
                'rowsReturned' => count($records),
            ],
        ];
    }

    protected function summaryCards(string $countryName, string $indicatorLabel, array $records, array $filters): array
    {
        $latest = $records === [] ? null : Arr::last($records);

        return [
            [
                'label' => 'Country',
                'value' => $countryName,
                'description' => 'Selected economy.',
            ],
            [
                'label' => 'Indicator',
                'value' => $indicatorLabel,
                'description' => 'Normalized dashboard indicator.',
            ],
            [
                'label' => 'Latest value',
                'value' => $latest['formatted_value'] ?? 'No data',
                'description' => $latest ? 'Latest published year: '.$latest['year'] : 'No published observation returned.',
            ],
            [
                'label' => 'Data points',
                'value' => (string) count($records),
                'description' => sprintf('Requested range: %d–%d.', $filters['startYear'], $filters['endYear']),
            ],
        ];
    }

    protected function providerWarning(array $indicator, array $records, array $filters): ?array
    {
        if ($indicator['is_proxy']) {
            return [
                'title' => 'Proxy indicator in use',
                'message' => $indicator['proxy_message'] ?? 'The selected source is using the closest available proxy for this indicator.',
            ];
        }

        $latestYear = Arr::last($records)['year'] ?? null;

        if ($latestYear !== null && $latestYear < (int) $filters['endYear']) {
            return [
                'title' => 'Latest published year is older than requested',
                'message' => sprintf(
                    'The latest returned %s observation is %d even though the selected end year is %d. This usually means more recent observations are not published yet for this series.',
                    $this->sourceLabel(),
                    $latestYear,
                    $filters['endYear'],
                ),
            ];
        }

        return null;
    }

    protected function methodologyNotes(array $indicator, array $records, array $filters): array
    {
        $notes = [
            sprintf('%s responses are normalized into the shared dashboard record structure before reaching the React view.', $this->sourceLabel()),
            $this->providerMethodologyNote(),
        ];

        if ($indicator['is_proxy']) {
            $notes[] = 'The debt requirement is currently satisfied with a fiscal debt proxy rather than an exact like-for-like public debt series.';
        }

        $latestYear = Arr::last($records)['year'] ?? null;

        if ($latestYear !== null && $latestYear < (int) $filters['endYear']) {
            $notes[] = sprintf(
                'Latest available observation is %d even though the requested end year was %d.',
                $latestYear,
                $filters['endYear'],
            );
        }

        return array_merge($notes, $this->additionalMethodologyNotes($indicator, $records, $filters));
    }

    protected function formatValue(float $value, array $indicator): string
    {
        return match ($indicator['format']) {
            'currency_compact' => '$'.$this->compactNumber($value),
            'currency_standard' => '$'.number_format($value, 2),
            'percent' => number_format($value, 2).'%',
            'population' => $this->compactNumber($value),
            default => number_format($value, 2),
        };
    }

    protected function compactNumber(float $value): string
    {
        $absolute = abs($value);

        if ($absolute >= 1_000_000_000_000) {
            return number_format($value / 1_000_000_000_000, 2).'T';
        }

        if ($absolute >= 1_000_000_000) {
            return number_format($value / 1_000_000_000, 2).'B';
        }

        if ($absolute >= 1_000_000) {
            return number_format($value / 1_000_000, 2).'M';
        }

        if ($absolute >= 1_000) {
            return number_format($value / 1_000, 2).'K';
        }

        return number_format($value, 2);
    }

    protected function indicatorConfig(string $normalizedKey): array
    {
        $indicator = config(sprintf(
            'economic-dashboard.providers.%s.indicator_map.%s',
            $this->providerConfigKey(),
            $normalizedKey,
        ));

        abort_if(! is_array($indicator), 404);

        return array_merge($indicator, [
            'label' => $indicator['label'] ?? ucfirst(str_replace('_', ' ', $normalizedKey)),
            'is_proxy' => (bool) ($indicator['is_proxy'] ?? false),
            'format' => $indicator['format'] ?? 'number',
            'unit' => $indicator['unit'] ?? '',
        ]);
    }

    protected function countryName(string $countryCode): string
    {
        return (string) (collect(config('economic-dashboard.default_countries', []))
            ->firstWhere('code', $countryCode)['name'] ?? $countryCode);
    }

    protected function cacheKey(array $filters, string $indicatorCode): string
    {
        return sprintf('%s:%s', $this->providerCachePrefix(), sha1(json_encode([
            'country' => $filters['country'],
            'indicator' => $filters['indicator'],
            'indicator_code' => $indicatorCode,
            'startYear' => $filters['startYear'],
            'endYear' => $filters['endYear'],
        ])));
    }

    protected function providerConfig(): array
    {
        return config(sprintf('economic-dashboard.providers.%s', $this->providerConfigKey()), []);
    }

    protected function additionalMethodologyNotes(array $indicator, array $records, array $filters): array
    {
        return [];
    }

    abstract protected function fetchSeries(array $filters, array $indicator): array;

    abstract protected function normalizeRecord(array $row, array $filters, array $indicator, string $countryName): ?array;

    abstract protected function providerConfigKey(): string;

    abstract protected function providerCachePrefix(): string;

    abstract protected function sourceLabel(): string;

    abstract protected function emptyStateMessage(): string;

    abstract protected function providerMethodologyNote(): string;

    abstract protected function providerErrorTitle(): string;
}
