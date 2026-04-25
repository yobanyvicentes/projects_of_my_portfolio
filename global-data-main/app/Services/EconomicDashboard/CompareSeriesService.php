<?php

namespace App\Services\EconomicDashboard;

use App\Services\EconomicDashboard\Providers\Dbnomics\DbnomicsSeriesService;
use App\Services\EconomicDashboard\Providers\WorldBank\WorldBankSeriesService;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CompareSeriesService
{
    public function __construct(
        protected DashboardCatalogService $catalog,
        protected WorldBankSeriesService $worldBankSeries,
        protected DbnomicsSeriesService $dbnomicsSeries,
    ) {
    }

    public function compare(array $filters): array
    {
        $sourceOptions = collect($this->catalog->liveCompareSources())
            ->keyBy('key');

        $results = collect();

        foreach ($filters['sourceKeys'] as $sourceKey) {
            foreach ($filters['countries'] as $countryCode) {
                $queryFilters = [
                    'country' => $countryCode,
                    'indicator' => $filters['indicator'],
                    'startYear' => $filters['startYear'],
                    'endYear' => $filters['endYear'],
                ];

                $dataset = $this->providerFor($sourceKey)->query($queryFilters);
                $results->push([
                    'sourceKey' => $sourceKey,
                    'sourceName' => $sourceOptions[$sourceKey]['name'] ?? $sourceKey,
                    'countryCode' => $countryCode,
                    'countryName' => $this->catalog->countryName($countryCode),
                    'dataset' => $dataset,
                ]);
            }
        }

        $records = $results
            ->flatMap(function (array $result) {
                return collect($result['dataset']['records'] ?? [])->map(function (array $record) use ($result) {
                    $record['compare_id'] = sprintf('%s-%s-%s', $result['sourceKey'], $result['countryCode'], $record['year']);
                    $record['comparison_label'] = $result['sourceName'].' · '.$result['countryName'];
                    return $record;
                });
            })
            ->sortBy(fn (array $record) => sprintf('%s-%s-%s', $record['year'], $record['source'], $record['country_name']))
            ->values();

        $chartSeries = $results
            ->filter(fn (array $result) => ($result['dataset']['queryState'] ?? null) === 'success')
            ->values()
            ->map(function (array $result, int $index) {
                return [
                    'sourceKey' => $result['sourceKey'],
                    'countryCode' => $result['countryCode'],
                    'dataKey' => 'series_'.($index + 1),
                    'name' => $result['sourceName'].' · '.$result['countryName'],
                    'points' => $result['dataset']['chartPoints'] ?? [],
                ];
            });

        $years = $records->pluck('year')->unique()->sort()->values();
        $chartData = $years->map(function ($year) use ($chartSeries) {
            $row = ['year' => (int) $year];

            foreach ($chartSeries as $series) {
                $point = collect($series['points'])->firstWhere('year', (int) $year);
                $row[$series['dataKey']] = $point['value'] ?? null;
            }

            return $row;
        })->values()->all();

        $summaryItems = [
            [
                'label' => 'Countries selected',
                'value' => (string) count($filters['countries']),
                'description' => collect($filters['countries'])->map(fn ($code) => $this->catalog->countryName($code))->join(', '),
            ],
            [
                'label' => 'Sources selected',
                'value' => (string) count($filters['sourceKeys']),
                'description' => collect($filters['sourceKeys'])->map(fn ($key) => $sourceOptions[$key]['name'] ?? $key)->join(', '),
            ],
            [
                'label' => 'Comparable observations',
                'value' => (string) $records->count(),
                'description' => 'Normalized observations combined across countries and providers.',
            ],
            [
                'label' => 'Requested range',
                'value' => sprintf('%d–%d', $filters['startYear'], $filters['endYear']),
                'description' => 'Applied to every country/provider query in the compare workflow.',
            ],
        ];

        $methodologyNotes = $results
            ->flatMap(function (array $result) {
                $notes = collect($result['dataset']['methodologyNotes'] ?? []);
                return $notes->map(fn ($note) => $result['sourceName'].' · '.$result['countryName'].': '.$note);
            })
            ->unique()
            ->values()
            ->all();

        $warnings = $results
            ->map(function (array $result) {
                $warning = $result['dataset']['warning'] ?? null;
                if (! is_array($warning)) {
                    return null;
                }
                return $result['sourceName'].' · '.$result['countryName'].': '.$warning['message'];
            })
            ->filter()
            ->values()
            ->all();

        $errors = $results
            ->map(function (array $result) {
                $error = $result['dataset']['error'] ?? null;
                if (! is_array($error)) {
                    return null;
                }
                return $result['sourceName'].' · '.$result['countryName'].': '.$error['message'];
            })
            ->filter()
            ->values()
            ->all();

        $statusRows = $results->map(function (array $result) {
            return [
                'id' => Str::slug($result['sourceKey'].'-'.$result['countryCode']),
                'source' => $result['sourceName'],
                'country' => $result['countryName'],
                'state' => ucfirst((string) ($result['dataset']['queryState'] ?? 'unknown')),
                'latest_year' => (string) (($result['dataset']['meta']['latestYear'] ?? '—')),
                'rows_returned' => (string) (($result['dataset']['meta']['rowsReturned'] ?? 0)),
            ];
        })->values()->all();

        $allFailed = $results->every(fn (array $result) => ($result['dataset']['queryState'] ?? null) === 'error');
        $allEmpty = $records->isEmpty() && ! $allFailed;

        return [
            'summaryItems' => $summaryItems,
            'chartData' => $chartData,
            'chartLines' => $chartSeries->map(fn (array $series) => [
                'dataKey' => $series['dataKey'],
                'name' => $series['name'],
            ])->values()->all(),
            'records' => $records->map(function (array $record) {
                return [
                    'id' => $record['compare_id'],
                    'source' => $record['source'],
                    'country_name' => $record['country_name'],
                    'normalized_indicator_label' => $record['normalized_indicator_label'],
                    'year' => $record['year'],
                    'formatted_value' => $record['formatted_value'],
                    'unit' => $record['unit'],
                    'notes' => $record['notes'],
                ];
            })->all(),
            'statusRows' => $statusRows,
            'warning' => $warnings !== [] ? [
                'title' => 'Comparison caveats detected',
                'message' => implode(' ', $warnings),
            ] : null,
            'error' => $allFailed ? [
                'title' => 'No provider returned usable comparison data',
                'message' => $errors !== [] ? implode(' ', $errors) : 'The compare workflow could not retrieve comparable series right now.',
            ] : null,
            'emptyState' => $allEmpty ? [
                'title' => 'No comparable observations were returned',
                'message' => 'At least one provider responded, but the selected countries, years or indicator did not produce matching usable observations for the compare workspace.',
            ] : null,
            'methodologyNotes' => array_values(array_unique(array_merge([
                'Live compare currently supports World Bank and DBnomics because both providers already expose normalized provider pages in this application.',
                'Comparison results are assembled by running the same normalized provider workflow used by the individual source pages.',
            ], $methodologyNotes))),
        ];
    }

    protected function providerFor(string $sourceKey): object
    {
        return match ($sourceKey) {
            'world-bank' => $this->worldBankSeries,
            'dbnomics' => $this->dbnomicsSeries,
            default => throw new \InvalidArgumentException('Unsupported compare provider: '.$sourceKey),
        };
    }
}
