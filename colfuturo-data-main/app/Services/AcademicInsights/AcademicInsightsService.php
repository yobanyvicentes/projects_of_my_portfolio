<?php

namespace App\Services\AcademicInsights;

use App\Models\ColfuturoProfile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AcademicInsightsService
{
    public function filters(): array
    {
        $years = $this->promotionYears();
        $minYear = $years->min();
        $maxYear = $years->max();

        return [
            'genders' => $this->pluckOptions('gender'),
            'departments' => $this->pluckOptions('department'),
            'undergraduate_universities' => $this->pluckOptions('undergraduate_university'),
            'undergraduate_programs' => $this->pluckOptions('undergraduate_program'),
            'postgraduate_universities' => $this->pluckOptions('postgraduate_university'),
            'countries' => $this->pluckOptions('country'),
            'destination_cities' => $this->pluckOptions('destination_city'),
            'postgraduate_types' => $this->pluckOptions('postgraduate_type'),
            'postgraduate_programs' => $this->pluckOptions('postgraduate_program'),
            'areas' => $this->pluckOptions('area'),
            'statuses' => $this->pluckOptions('status'),
            'promotion_year' => [
                'min' => $minYear,
                'max' => $maxYear,
                'available' => $minYear !== null && $maxYear !== null && $maxYear >= $minYear,
            ],
        ];
    }

    public function dashboard(array $filters = []): array
    {
        $baseQuery = ColfuturoProfile::query()->filter($filters);

        return [
            'kpis' => [
                'total_profiles' => (clone $baseQuery)->count(),
                'beneficiaries' => (clone $baseQuery)->where('status', 'Beneficiario')->count(),
                'selected' => (clone $baseQuery)->where('status', 'Seleccionado')->count(),
                'countries' => (clone $baseQuery)->distinct('country')->whereNotNull('country')->count('country'),
                'postgraduate_universities' => (clone $baseQuery)->distinct('postgraduate_university')->whereNotNull('postgraduate_university')->count('postgraduate_university'),
                'male_total' => (clone $baseQuery)->whereRaw($this->maleSql())->count(),
                'female_total' => (clone $baseQuery)->whereRaw($this->femaleSql())->count(),
            ],
            'top_10_countries' => $this->topByWithGender((clone $baseQuery), 'country'),
            'top_10_universities' => $this->topByWithGender((clone $baseQuery), 'postgraduate_university'),
            'top_10_areas' => $this->topByWithGender((clone $baseQuery), 'area'),
            'top_10_departments' => $this->topByWithGender((clone $baseQuery), 'department'),
            'origin_map' => $this->originMap((clone $baseQuery)),
            'destination_map' => $this->destinationMap((clone $baseQuery)),
        ];
    }

    public function explorer(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return ColfuturoProfile::query()
            ->filter($filters)
            ->orderByDesc('promotion_year')
            ->orderBy('country')
            ->orderBy('postgraduate_university')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function recommendations(array $profile): array
    {
        $weights = [
            'undergraduate_program' => 25,
            'area' => 30,
            'postgraduate_type' => 20,
            'undergraduate_university' => 15,
            'department' => 10,
        ];

        $candidates = ColfuturoProfile::query()
            ->when($profile['area'] ?? null, fn ($query, $value) => $query->where('area', $value))
            ->when($profile['postgraduate_type'] ?? null, fn ($query, $value) => $query->where('postgraduate_type', $value))
            ->get();

        $scored = $candidates->map(function (ColfuturoProfile $candidate) use ($profile, $weights) {
            $score = 0;

            foreach ($weights as $field => $weight) {
                if (! empty($profile[$field]) && $candidate->{$field} === $profile[$field]) {
                    $score += $weight;
                }
            }

            return [
                'profile' => $candidate,
                'score' => $score,
            ];
        })->filter(fn (array $item) => $item['score'] > 0)
            ->sortByDesc('score')
            ->values();

        $matchProfiles = new Collection($scored->take(25)->pluck('profile')->all());

        return [
            'top_matches' => $scored->take(10)->all(),
            'recommended_countries' => $this->groupProfiles($matchProfiles, 'country'),
            'recommended_universities' => $this->groupProfiles($matchProfiles, 'postgraduate_university'),
            'recommended_programs' => $this->groupProfiles($matchProfiles, 'postgraduate_program'),
            'recommended_cities' => $this->groupProfiles($matchProfiles, 'destination_city'),
            'explanations' => $this->explanations($profile, $matchProfiles),
        ];
    }

    protected function explanations(array $profile, Collection $profiles): array
    {
        if ($profiles->isEmpty()) {
            return ['No high-confidence matches were found yet. Try removing one or two filters to broaden the recommendation pool.'];
        }

        $messages = [];

        if ($profile['area'] ?? null) {
            $messages[] = 'The engine prioritizes the same academic area to keep the comparison relevant.';
        }

        if ($profile['postgraduate_type'] ?? null) {
            $messages[] = 'Program type receives a strong weight so master profiles are mainly compared with master profiles, and doctorate profiles with doctorate profiles.';
        }

        if ($profile['undergraduate_program'] ?? null) {
            $messages[] = 'Undergraduate background alignment helps surface destinations chosen by people with similar disciplinary foundations.';
        }

        return $messages;
    }

    protected function originMap($query): array
    {
        $coordinates = GeoCatalog::colombiaDepartments();

        return $query
            ->select('department', DB::raw('COUNT(*) as total'))
            ->whereNotNull('department')
            ->groupBy('department')
            ->orderByDesc('total')
            ->get()
            ->map(function ($row) use ($coordinates) {
                $point = $coordinates[$row->department] ?? null;

                return [
                    'name' => $row->department,
                    'total' => (int) $row->total,
                    'lat' => $point['lat'] ?? null,
                    'lng' => $point['lng'] ?? null,
                ];
            })
            ->filter(fn (array $row) => $row['lat'] !== null && $row['lng'] !== null)
            ->values()
            ->all();
    }

    protected function destinationMap($query): array
    {
        $coordinates = GeoCatalog::countries();

        return $query
            ->select('country', DB::raw('COUNT(*) as total'))
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('total')
            ->get()
            ->map(function ($row) use ($coordinates) {
                $point = $coordinates[$row->country] ?? null;

                return [
                    'name' => $row->country,
                    'total' => (int) $row->total,
                    'lat' => $point['lat'] ?? null,
                    'lng' => $point['lng'] ?? null,
                ];
            })
            ->filter(fn (array $row) => $row['lat'] !== null && $row['lng'] !== null)
            ->values()
            ->all();
    }

    protected function topByWithGender($query, string $column, int $limit = 10): array
    {
        $maleSql = $this->maleSql();
        $femaleSql = $this->femaleSql();

        return $query
            ->select(
                $column,
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN {$maleSql} THEN 1 ELSE 0 END) as male_total"),
                DB::raw("SUM(CASE WHEN {$femaleSql} THEN 1 ELSE 0 END) as female_total")
            )
            ->whereNotNull($column)
            ->groupBy($column)
            ->orderByDesc('total')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => [
                'label' => $row->{$column},
                'total' => (int) $row->total,
                'male_total' => (int) $row->male_total,
                'female_total' => (int) $row->female_total,
            ])
            ->all();
    }

    protected function maleSql(): string
    {
        return "(LOWER(COALESCE(gender, '')) LIKE '%masc%' OR LOWER(COALESCE(gender, '')) = 'm' OR LOWER(COALESCE(gender, '')) LIKE '%homb%')";
    }

    protected function femaleSql(): string
    {
        return "(LOWER(COALESCE(gender, '')) LIKE '%fem%' OR LOWER(COALESCE(gender, '')) = 'f' OR LOWER(COALESCE(gender, '')) LIKE '%muj%')";
    }

    protected function groupProfiles(Collection $profiles, string $column): array
    {
        return $profiles
            ->groupBy($column)
            ->map(fn (Collection $group, $label) => ['label' => $label, 'total' => $group->count()])
            ->sortByDesc('total')
            ->take(10)
            ->values()
            ->all();
    }

    protected function pluckOptions(string $column): array
    {
        return ColfuturoProfile::query()
            ->whereNotNull($column)
            ->where($column, '!=', '')
            ->orderBy($column)
            ->distinct()
            ->pluck($column)
            ->values()
            ->all();
    }

    protected function promotionYears()
    {
        return ColfuturoProfile::query()
            ->whereNotNull('promotion_year')
            ->pluck('promotion_year')
            ->map(function ($year) {
                if ($year === null) {
                    return null;
                }

                $value = trim((string) $year);

                if ($value === '') {
                    return null;
                }

                if (preg_match('/(19|20)\d{2}/', $value, $matches) !== 1) {
                    return null;
                }

                $parsed = (int) $matches[0];

                return $parsed >= 1900 && $parsed <= 2100 ? $parsed : null;
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }
}
