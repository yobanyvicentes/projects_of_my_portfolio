<?php

namespace App\Http\Requests;

use App\Enums\CompetitiveStrategy;
use App\Enums\MarketType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateScenarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'market_type' => ['required', Rule::enum(MarketType::class)],
            'competitive_strategy' => ['required', Rule::enum(CompetitiveStrategy::class)],
            'company_a_price' => ['required', 'numeric', 'min:0'],
            'company_b_price' => ['required', 'numeric', 'min:0'],
            'company_a_ad_budget' => ['required', 'numeric', 'min:0'],
            'company_b_ad_budget' => ['required', 'numeric', 'min:0'],
            'consumers_count' => ['required', 'integer', 'min:1'],
            'periods_count' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter a scenario name.',
            'market_type.required' => 'Please select a market type.',
            'competitive_strategy.required' => 'Please select a competitive strategy.',
            'company_a_price.required' => 'Please enter the price for Company A.',
            'company_b_price.required' => 'Please enter the price for Company B.',
            'company_a_ad_budget.required' => 'Please enter the advertising budget for Company A.',
            'company_b_ad_budget.required' => 'Please enter the advertising budget for Company B.',
            'consumers_count.required' => 'Please enter the number of consumers.',
            'periods_count.required' => 'Please enter the number of periods.',
        ];
    }
}
