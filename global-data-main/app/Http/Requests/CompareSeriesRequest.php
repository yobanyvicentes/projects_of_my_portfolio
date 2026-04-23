<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class CompareSeriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $defaultRange = config('economic-dashboard.default_year_range', [
            'from' => 2014,
            'to' => 2024,
        ]);

        $countries = $this->input('countries', ['NZL', 'COL', 'USA']);
        $sourceKeys = $this->input('sourceKeys', ['world-bank', 'dbnomics']);

        $this->merge([
            'countries' => collect(is_array($countries) ? $countries : [$countries])
                ->map(fn ($country) => strtoupper((string) $country))
                ->filter()
                ->unique()
                ->values()
                ->all(),
            'sourceKeys' => collect(is_array($sourceKeys) ? $sourceKeys : [$sourceKeys])
                ->map(fn ($source) => (string) $source)
                ->filter()
                ->unique()
                ->values()
                ->all(),
            'indicator' => (string) $this->input('indicator', 'gdp'),
            'startYear' => (int) $this->input('startYear', $defaultRange['from']),
            'endYear' => (int) $this->input('endYear', $defaultRange['to']),
        ]);
    }

    public function rules(): array
    {
        $countryCodes = collect(config('economic-dashboard.default_countries', []))
            ->pluck('code')
            ->filter()
            ->values()
            ->all();

        $indicatorKeys = collect(config('economic-dashboard.default_indicators', []))
            ->pluck('key')
            ->filter()
            ->values()
            ->all();

        $allowedSources = ['world-bank', 'dbnomics'];
        $defaultRange = config('economic-dashboard.default_year_range', ['from' => 2014, 'to' => 2024]);

        return [
            'countries' => ['required', 'array', 'min:1', 'max:4'],
            'countries.*' => ['required', 'string', Rule::in($countryCodes)],
            'sourceKeys' => ['required', 'array', 'min:1', 'max:2'],
            'sourceKeys.*' => ['required', 'string', Rule::in($allowedSources)],
            'indicator' => ['required', 'string', Rule::in($indicatorKeys)],
            'startYear' => ['required', 'integer', 'min:1960', 'max:2100'],
            'endYear' => ['required', 'integer', 'min:1960', 'max:2100', 'gte:startYear'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $defaultRange = config('economic-dashboard.default_year_range', ['from' => 2014, 'to' => 2024]);
            $startYear = (int) $this->input('startYear');
            $endYear = (int) $this->input('endYear');

            if ($startYear < $defaultRange['from'] || $startYear > $defaultRange['to']) {
                $validator->errors()->add('startYear', sprintf('Start year must be between %d and %d.', $defaultRange['from'], $defaultRange['to']));
            }

            if ($endYear < $defaultRange['from'] || $endYear > $defaultRange['to']) {
                $validator->errors()->add('endYear', sprintf('End year must be between %d and %d.', $defaultRange['from'], $defaultRange['to']));
            }
        });
    }
}
