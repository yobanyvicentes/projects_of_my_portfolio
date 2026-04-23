<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class WorldBankSeriesRequest extends FormRequest
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

        $this->merge([
            'country' => strtoupper((string) $this->input('country', 'NZL')),
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

        $defaultRange = config('economic-dashboard.default_year_range', [
            'from' => 2014,
            'to' => 2024,
        ]);

        return [
            'country' => ['required', 'string', Rule::in($countryCodes)],
            'indicator' => ['required', 'string', Rule::in($indicatorKeys)],
            'startYear' => ['required', 'integer', 'min:1960', 'max:2100'],
            'endYear' => [
                'required',
                'integer',
                'min:1960',
                'max:2100',
                'gte:startYear',
                'min:'.$defaultRange['from'],
                'max:'.$defaultRange['to'],
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $defaultRange = config('economic-dashboard.default_year_range', [
                'from' => 2014,
                'to' => 2024,
            ]);

            $startYear = (int) $this->input('startYear');
            $endYear = (int) $this->input('endYear');

            if ($startYear < $defaultRange['from'] || $startYear > $defaultRange['to']) {
                $validator->errors()->add('startYear', sprintf(
                    'Start year must be between %d and %d.',
                    $defaultRange['from'],
                    $defaultRange['to'],
                ));
            }

            if ($endYear < $defaultRange['from'] || $endYear > $defaultRange['to']) {
                $validator->errors()->add('endYear', sprintf(
                    'End year must be between %d and %d.',
                    $defaultRange['from'],
                    $defaultRange['to'],
                ));
            }
        });
    }

    public function messages(): array
    {
        return [
            'country.in' => 'Please select one of the supported countries for this initial World Bank release.',
            'indicator.in' => 'Please select one of the supported normalized indicators.',
            'endYear.gte' => 'End year must be greater than or equal to start year.',
        ];
    }
}
