<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompareSeriesRequest;
use App\Http\Requests\SeriesQueryRequest;
use App\Services\EconomicDashboard\CompareSeriesService;
use App\Services\EconomicDashboard\DashboardCatalogService;
use App\Services\EconomicDashboard\Providers\Dbnomics\DbnomicsSeriesService;
use App\Services\EconomicDashboard\Providers\WorldBank\WorldBankSeriesService;
use Inertia\Inertia;
use Inertia\Response;

class EconomicDashboardPageController extends Controller
{
    public function __construct(
        protected DashboardCatalogService $catalog,
    ) {
    }

    public function home(): Response
    {
        return Inertia::render('Home', [
            'appName' => config('app.name', 'Global Economic Dashboard'),
            'navigation' => $this->catalog->navigation(),
            'sources' => $this->catalog->sources(),
            'defaultCountries' => $this->catalog->countries(),
            'defaultIndicators' => $this->catalog->indicators(),
            'defaultYearRange' => $this->catalog->yearRange(),
            'platformHighlights' => $this->catalog->platformHighlights(),
            'normalizedModelFields' => $this->catalog->normalizedModelFields(),
            'roadmap' => $this->catalog->roadmap(),
        ]);
    }

    public function worldBank(
        SeriesQueryRequest $request,
        WorldBankSeriesService $worldBankSeries,
    ): Response {
        $filters = $request->validated();

        return $this->liveProviderPage('world-bank', $filters, $worldBankSeries->query($filters));
    }

    public function imf(): Response
    {
        return $this->providerPage('imf');
    }

    public function oecd(): Response
    {
        return $this->providerPage('oecd');
    }

    public function unData(): Response
    {
        return $this->providerPage('un-data');
    }

    public function dbnomics(
        SeriesQueryRequest $request,
        DbnomicsSeriesService $dbnomicsSeries,
    ): Response {
        $filters = $request->validated();

        return $this->liveProviderPage('dbnomics', $filters, $dbnomicsSeries->query($filters));
    }

    public function fred(): Response
    {
        return $this->providerPage('fred');
    }

    public function compare(
        CompareSeriesRequest $request,
        CompareSeriesService $compareSeries,
    ): Response {
        $filters = $request->validated();

        return Inertia::render('ComparePage', [
            'appName' => config('app.name', 'Global Economic Dashboard'),
            'navigation' => $this->catalog->navigation(),
            'sources' => $this->catalog->liveCompareSources(),
            'countryOptions' => $this->catalog->countries(),
            'indicatorOptions' => $this->catalog->indicators(),
            'defaultYearRange' => $this->catalog->yearRange(),
            'filters' => $filters,
            'compareDataset' => $compareSeries->compare($filters),
            'comparisonPrinciples' => $this->catalog->comparisonPrinciples(),
        ]);
    }

    protected function providerPage(string $sourceKey): Response
    {
        return Inertia::render('ProviderPage', [
            'appName' => config('app.name', 'Global Economic Dashboard'),
            'navigation' => $this->catalog->navigation(),
            'source' => $this->catalog->source($sourceKey),
            'countryOptions' => $this->catalog->countries(),
            'indicatorOptions' => $this->catalog->indicators(),
            'defaultYearRange' => $this->catalog->yearRange(),
            'initialFilters' => [
                'country' => 'NZL',
                'indicator' => 'gdp',
                'startYear' => $this->catalog->yearRange()['from'],
                'endYear' => $this->catalog->yearRange()['to'],
            ],
            'previewSummary' => $this->catalog->providerPreviewSummary($sourceKey),
            'previewRows' => $this->catalog->providerPreviewRows($sourceKey),
            'uiStates' => $this->catalog->uiStates(),
        ]);
    }

    protected function liveProviderPage(string $sourceKey, array $filters, array $dataset): Response
    {
        return Inertia::render('ProviderLivePage', [
            'appName' => config('app.name', 'Global Economic Dashboard'),
            'navigation' => $this->catalog->navigation(),
            'source' => $this->catalog->source($sourceKey),
            'countryOptions' => $this->catalog->countries(),
            'indicatorOptions' => $this->catalog->indicators(),
            'defaultYearRange' => $this->catalog->yearRange(),
            'filters' => $filters,
            'dataset' => $dataset,
        ]);
    }
}
