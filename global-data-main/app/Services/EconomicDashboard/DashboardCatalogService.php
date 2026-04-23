<?php

namespace App\Services\EconomicDashboard;

use Illuminate\Support\Arr;

class DashboardCatalogService
{
    public function navigation(): array
    {
        return collect(config('economic-dashboard.sources'))
            ->map(fn (array $source, string $key) => [
                'label' => $source['nav_label'],
                'href' => $source['path'],
                'key' => $key,
            ])
            ->prepend([
                'label' => 'Overview',
                'href' => '/',
                'key' => 'home',
            ])
            ->push([
                'label' => 'Compare',
                'href' => '/compare',
                'key' => 'compare',
            ])
            ->values()
            ->all();
    }

    public function sources(): array
    {
        return collect(config('economic-dashboard.sources'))
            ->map(fn (array $source, string $key) => $this->formatSource($key, $source))
            ->values()
            ->all();
    }

    public function source(string $sourceKey): array
    {
        $source = config("economic-dashboard.sources.{$sourceKey}");

        abort_if(! is_array($source), 404);

        return $this->formatSource($sourceKey, $source);
    }

    public function liveCompareSources(): array
    {
        return collect(['world-bank', 'dbnomics'])
            ->map(fn (string $key) => $this->source($key))
            ->values()
            ->all();
    }

    public function countries(): array
    {
        return config('economic-dashboard.default_countries', []);
    }

    public function countryName(string $countryCode): string
    {
        return (string) (collect($this->countries())
            ->firstWhere('code', $countryCode)['name'] ?? $countryCode);
    }

    public function indicators(): array
    {
        return config('economic-dashboard.default_indicators', []);
    }

    public function yearRange(): array
    {
        return config('economic-dashboard.default_year_range', [
            'from' => now()->subYears(10)->year,
            'to' => now()->year,
        ]);
    }

    public function platformHighlights(): array
    {
        return [
            [
                'label' => 'Sources planned',
                'value' => (string) count(config('economic-dashboard.sources', [])),
                'description' => 'World Bank, IMF, OECD, UN Data, DBnomics and FRED.',
            ],
            [
                'label' => 'Initial indicators',
                'value' => (string) count(config('economic-dashboard.default_indicators', [])),
                'description' => 'GDP, GDP per capita, inflation, unemployment, population and public debt.',
            ],
            [
                'label' => 'Default countries',
                'value' => (string) count(config('economic-dashboard.default_countries', [])),
                'description' => 'New Zealand, Colombia, United States and Australia.',
            ],
            [
                'label' => 'Architecture goal',
                'value' => 'Senior-ready',
                'description' => 'Thin controllers, provider services, normalized records and resilient UI states.',
            ],
        ];
    }

    public function normalizedModelFields(): array
    {
        return config('economic-dashboard.normalized_model_fields', []);
    }

    public function roadmap(): array
    {
        return [
            [
                'title' => 'PR1 — App shell and analytics workspace',
                'description' => 'Professional layout, navigation, provider pages, comparison shell and reusable React components.',
            ],
            [
                'title' => 'PR2 — World Bank live integration',
                'description' => 'First provider service, request validation, caching strategy and normalized response mapping.',
            ],
            [
                'title' => 'PR3 — Shared normalization and additional providers',
                'description' => 'Provider abstractions, error handling and cross-source mapping foundations.',
            ],
            [
                'title' => 'PR4 — Charts and reusable data widgets',
                'description' => 'Reusable line and bar chart components tailored for economic series.',
            ],
            [
                'title' => 'PR5 — Comparison workflow',
                'description' => 'Cross-country and multi-source UX with live provider orchestration and methodology warnings.',
            ],
            [
                'title' => 'PR6 — Polish and resilience',
                'description' => 'Final UX, error states, explanatory notes and portfolio-level refinements.',
            ],
        ];
    }

    public function comparisonPrinciples(): array
    {
        return [
            [
                'title' => 'Normalize before comparing',
                'description' => 'Every provider response will be transformed to a shared record structure before reaching the UI.',
            ],
            [
                'title' => 'Display methodology differences',
                'description' => 'The compare page will flag when an indicator is proxied or not strictly equivalent across providers.',
            ],
            [
                'title' => 'Fail gracefully',
                'description' => 'If one provider times out or lacks a series, the rest of the analysis workspace should remain usable.',
            ],
        ];
    }

    public function providerPreviewSummary(string $sourceKey): array
    {
        $source = $this->source($sourceKey);

        return [
            [
                'label' => 'Coverage focus',
                'value' => $source['focus'],
                'description' => 'Primary analytical angle for this provider page.',
            ],
            [
                'label' => 'Current phase',
                'value' => 'UI shell',
                'description' => 'Live data integration is intentionally deferred to the provider integration PRs.',
            ],
            [
                'label' => 'Default indicator',
                'value' => 'GDP',
                'description' => 'Filters are already structured around the normalized indicator catalog.',
            ],
        ];
    }

    public function providerPreviewRows(string $sourceKey): array
    {
        $source = $this->source($sourceKey);

        return collect([
            ['country' => 'New Zealand', 'country_code' => 'NZL', 'year' => 2021, 'value' => 'Preview', 'unit' => 'Pending provider binding'],
            ['country' => 'New Zealand', 'country_code' => 'NZL', 'year' => 2022, 'value' => 'Preview', 'unit' => 'Pending provider binding'],
            ['country' => 'New Zealand', 'country_code' => 'NZL', 'year' => 2023, 'value' => 'Preview', 'unit' => 'Pending provider binding'],
        ])->map(function (array $row) use ($source) {
            return array_merge($row, [
                'source' => $source['name'],
                'indicator' => 'GDP',
            ]);
        })->all();
    }

    public function uiStates(): array
    {
        return [
            'idle' => [
                'title' => 'No live series requested yet',
                'message' => 'The filters and layout are wired. Live provider requests, cache keys and normalization arrive in the next PRs.',
            ],
            'loading' => [
                'title' => 'Loading state ready',
                'message' => 'Reusable loading components are included so provider pages can feel responsive during API requests.',
            ],
            'error' => [
                'title' => 'Error handling planned from day one',
                'message' => 'Each provider page reserves space for friendly API failures and methodology caveats.',
            ],
        ];
    }

    protected function formatSource(string $key, array $source): array
    {
        return [
            'key' => $key,
            'name' => $source['name'],
            'navLabel' => $source['nav_label'],
            'path' => $source['path'],
            'tagline' => $source['tagline'],
            'focus' => $source['focus'],
            'description' => $source['description'],
            'methodologyNotice' => $source['methodology_notice'],
            'plannedNextStep' => $source['planned_next_step'],
            'coverageBullets' => Arr::wrap($source['coverage_bullets']),
        ];
    }
}
