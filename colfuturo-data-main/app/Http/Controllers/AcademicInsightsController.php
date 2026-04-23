<?php

namespace App\Http\Controllers;

use App\Services\AcademicInsights\AcademicInsightsService;
use Illuminate\Http\Request;


//
class AcademicInsightsController extends Controller
{
    public function __construct(private readonly AcademicInsightsService $service)
    {
    }

    public function index(Request $request)
    {
        $filters = $this->resolveFilters($request);

        return view('academic-insights.index', [
            'filters' => $this->service->filters(),
            'activeFilters' => $filters,
            'dashboard' => $this->service->dashboard($filters),
            'profiles' => $this->service->explorer($filters),
        ]);
    }

    public function recommend(Request $request)
    {
        $profile = $request->validate([
            'postgraduate_type' => ['nullable', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:255'],
        ]);

        return response()->json($this->service->recommendations($profile));
    }

    private function resolveFilters(Request $request): array
    {
        $filters = $request->only([
            'search',
            'gender',
            'department',
            'undergraduate_university',
            'undergraduate_program',
            'postgraduate_university',
            'country',
            'destination_city',
            'postgraduate_type',
            'postgraduate_program',
            'area',
            'status',
            'promotion_year_min',
            'promotion_year_max',
        ]);

        $yearMin = $filters['promotion_year_min'] ?? null;
        $yearMax = $filters['promotion_year_max'] ?? null;

        $filters['promotion_year_min'] = ($yearMin !== null && $yearMin !== '') ? (int) $yearMin : null;
        $filters['promotion_year_max'] = ($yearMax !== null && $yearMax !== '') ? (int) $yearMax : null;

        if (
            $filters['promotion_year_min'] !== null
            && $filters['promotion_year_max'] !== null
            && $filters['promotion_year_max'] < $filters['promotion_year_min']
        ) {
            [$filters['promotion_year_min'], $filters['promotion_year_max']] = [
                $filters['promotion_year_max'],
                $filters['promotion_year_min'],
            ];
        }

        return $filters;
    }
}
