@php
    $scenarioModel = $scenario ?? null;

    /*
    |--------------------------------------------------------------------------
    | Supported options only
    |--------------------------------------------------------------------------
    | Keep these values aligned with:
    | - validation rules
    | - database values
    | - simulation logic
    */

    $marketTypes = [
        'duopoly' => 'Duopoly',
        'monopolistic_competition' => 'Monopolistic Competition',
        'perfect_competition' => 'Perfect Competition',
    ];

    $competitiveStrategies = [
        'price_competition' => 'Price Competition',
        'advertising_competition' => 'Advertising Competition',
        'balanced' => 'Balanced Strategy',
    ];

    $inputClass = 'mt-2 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 dark:focus:border-indigo-400 dark:focus:ring-indigo-400';
    $labelClass = 'flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300';
    $helpClass = 'mt-2 text-xs leading-5 text-gray-500 dark:text-gray-400';
    $errorClass = 'mt-2 text-sm text-red-600 dark:text-red-400';

    $tooltipButtonClass = 'inline-flex h-5 w-5 items-center justify-center rounded-full border border-gray-300 bg-white text-[11px] font-semibold text-gray-500 transition hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:text-white';

    $tooltipPanelClass = 'pointer-events-none invisible absolute bottom-full left-1/2 z-30 mb-2 w-72 -translate-x-1/2 rounded-lg bg-gray-900 px-3 py-2 text-xs font-normal leading-5 text-white opacity-0 shadow-lg transition duration-150 group-hover:visible group-hover:opacity-100 group-focus-within:visible group-focus-within:opacity-100 dark:bg-gray-700';
@endphp

<div class="space-y-8">

    <fieldset class="space-y-6">
        <legend class="text-base font-semibold text-gray-900 dark:text-gray-100">
            Scenario Details
        </legend>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            {{-- Scenario Name --}}
            <div>
                <label for="name" class="{{ $labelClass }}">
                    <span>Scenario Name</span>

                    <span class="group relative inline-flex">
                        <button
                            type="button"
                            class="{{ $tooltipButtonClass }}"
                            aria-label="Help for Scenario Name"
                        >
                            ?
                        </button>

                        <span class="{{ $tooltipPanelClass }}" role="tooltip">
                            Give this scenario a short name so you can identify it later.
                        </span>
                    </span>
                </label>

                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $scenarioModel->name ?? '') }}"
                    placeholder="e.g. Baseline Scenario"
                    required
                    maxlength="120"
                    aria-describedby="name_help"
                    class="{{ $inputClass }}"
                >

                <p id="name_help" class="{{ $helpClass }}">
                    A short name to identify this simulation.
                </p>

                @error('name')
                    <p class="{{ $errorClass }}">{{ $message }}</p>
                @enderror
            </div>

            {{-- Market Type --}}
            <div>
                <label for="market_type" class="{{ $labelClass }}">
                    <span>Market Type</span>

                    <span class="group relative inline-flex">
                        <button
                            type="button"
                            class="{{ $tooltipButtonClass }}"
                            aria-label="Help for Market Type"
                        >
                            ?
                        </button>

                        <span class="{{ $tooltipPanelClass }}" role="tooltip">
                            Choose the type of market for this scenario, such as two-company competition or a highly competitive market.
                        </span>
                    </span>
                </label>

                <select
                    name="market_type"
                    id="market_type"
                    required
                    aria-describedby="market_type_help"
                    class="{{ $inputClass }}"
                >
                    <option value="">Select a market type</option>
                    @foreach ($marketTypes as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(old('market_type', $scenarioModel->market_type ?? '') === $value)
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <p id="market_type_help" class="{{ $helpClass }}">
                    Select the market structure for the simulation.
                </p>

                @error('market_type')
                    <p class="{{ $errorClass }}">{{ $message }}</p>
                @enderror
            </div>

            {{-- Competitive Strategy --}}
            <div class="md:col-span-2">
                <label for="competitive_strategy" class="{{ $labelClass }}">
                    <span>Competitive Strategy</span>

                    <span class="group relative inline-flex">
                        <button
                            type="button"
                            class="{{ $tooltipButtonClass }}"
                            aria-label="Help for Competitive Strategy"
                        >
                            ?
                        </button>

                        <span class="{{ $tooltipPanelClass }}" role="tooltip">
                            Choose how the companies mainly compete in this scenario: by price, by advertising, or by using both.
                        </span>
                    </span>
                </label>

                <select
                    name="competitive_strategy"
                    id="competitive_strategy"
                    required
                    aria-describedby="competitive_strategy_help"
                    class="{{ $inputClass }}"
                >
                    <option value="">Select a competitive strategy</option>
                    @foreach ($competitiveStrategies as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(old('competitive_strategy', $scenarioModel->competitive_strategy ?? '') === $value)
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <p id="competitive_strategy_help" class="{{ $helpClass }}">
                    Select how the companies compete in this scenario.
                </p>

                @error('competitive_strategy')
                    <p class="{{ $errorClass }}">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </fieldset>

    <fieldset class="space-y-6">
        <legend class="text-base font-semibold text-gray-900 dark:text-gray-100">
            Company Inputs
        </legend>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            {{-- Company A Price --}}
            <div>
                <label for="company_a_price" class="{{ $labelClass }}">
                    <span>Company A Price</span>

                    <span class="group relative inline-flex">
                        <button
                            type="button"
                            class="{{ $tooltipButtonClass }}"
                            aria-label="Help for Company A Price"
                        >
                            ?
                        </button>

                        <span class="{{ $tooltipPanelClass }}" role="tooltip">
                            Enter the starting price per unit for Company A.
                        </span>
                    </span>
                </label>

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    inputmode="decimal"
                    name="company_a_price"
                    id="company_a_price"
                    value="{{ old('company_a_price', $scenarioModel->company_a_price ?? '') }}"
                    placeholder="20.00"
                    required
                    aria-describedby="company_a_price_help"
                    class="{{ $inputClass }}"
                >

                <p id="company_a_price_help" class="{{ $helpClass }}">
                    Starting price for Company A.
                </p>

                @error('company_a_price')
                    <p class="{{ $errorClass }}">{{ $message }}</p>
                @enderror
            </div>

            {{-- Company B Price --}}
            <div>
                <label for="company_b_price" class="{{ $labelClass }}">
                    <span>Company B Price</span>

                    <span class="group relative inline-flex">
                        <button
                            type="button"
                            class="{{ $tooltipButtonClass }}"
                            aria-label="Help for Company B Price"
                        >
                            ?
                        </button>

                        <span class="{{ $tooltipPanelClass }}" role="tooltip">
                            Enter the starting price per unit for Company B.
                        </span>
                    </span>
                </label>

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    inputmode="decimal"
                    name="company_b_price"
                    id="company_b_price"
                    value="{{ old('company_b_price', $scenarioModel->company_b_price ?? '') }}"
                    placeholder="18.00"
                    required
                    aria-describedby="company_b_price_help"
                    class="{{ $inputClass }}"
                >

                <p id="company_b_price_help" class="{{ $helpClass }}">
                    Starting price for Company B.
                </p>

                @error('company_b_price')
                    <p class="{{ $errorClass }}">{{ $message }}</p>
                @enderror
            </div>

            {{-- Company A Advertising Budget --}}
            <div>
                <label for="company_a_ad_budget" class="{{ $labelClass }}">
                    <span>Company A Advertising Budget</span>

                    <span class="group relative inline-flex">
                        <button
                            type="button"
                            class="{{ $tooltipButtonClass }}"
                            aria-label="Help for Company A Advertising Budget"
                        >
                            ?
                        </button>

                        <span class="{{ $tooltipPanelClass }}" role="tooltip">
                            Enter the advertising amount for Company A in this simulation.
                        </span>
                    </span>
                </label>

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    inputmode="decimal"
                    name="company_a_ad_budget"
                    id="company_a_ad_budget"
                    value="{{ old('company_a_ad_budget', $scenarioModel->company_a_ad_budget ?? '') }}"
                    placeholder="200.00"
                    required
                    aria-describedby="company_a_ad_budget_help"
                    class="{{ $inputClass }}"
                >

                <p id="company_a_ad_budget_help" class="{{ $helpClass }}">
                    Advertising budget for Company A.
                </p>

                @error('company_a_ad_budget')
                    <p class="{{ $errorClass }}">{{ $message }}</p>
                @enderror
            </div>

            {{-- Company B Advertising Budget --}}
            <div>
                <label for="company_b_ad_budget" class="{{ $labelClass }}">
                    <span>Company B Advertising Budget</span>

                    <span class="group relative inline-flex">
                        <button
                            type="button"
                            class="{{ $tooltipButtonClass }}"
                            aria-label="Help for Company B Advertising Budget"
                        >
                            ?
                        </button>

                        <span class="{{ $tooltipPanelClass }}" role="tooltip">
                            Enter the advertising amount for Company B in this simulation.
                        </span>
                    </span>
                </label>

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    inputmode="decimal"
                    name="company_b_ad_budget"
                    id="company_b_ad_budget"
                    value="{{ old('company_b_ad_budget', $scenarioModel->company_b_ad_budget ?? '') }}"
                    placeholder="150.00"
                    required
                    aria-describedby="company_b_ad_budget_help"
                    class="{{ $inputClass }}"
                >

                <p id="company_b_ad_budget_help" class="{{ $helpClass }}">
                    Advertising budget for Company B.
                </p>

                @error('company_b_ad_budget')
                    <p class="{{ $errorClass }}">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </fieldset>

    <fieldset class="space-y-6">
        <legend class="text-base font-semibold text-gray-900 dark:text-gray-100">
            Simulation Settings
        </legend>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            {{-- Number of Consumers --}}
            <div>
                <label for="consumers_count" class="{{ $labelClass }}">
                    <span>Number of Consumers</span>

                    <span class="group relative inline-flex">
                        <button
                            type="button"
                            class="{{ $tooltipButtonClass }}"
                            aria-label="Help for Number of Consumers"
                        >
                            ?
                        </button>

                        <span class="{{ $tooltipPanelClass }}" role="tooltip">
                            Enter how many consumers will be included in the simulation.
                        </span>
                    </span>
                </label>

                <input
                    type="number"
                    min="1"
                    step="1"
                    inputmode="numeric"
                    name="consumers_count"
                    id="consumers_count"
                    value="{{ old('consumers_count', $scenarioModel->consumers_count ?? '') }}"
                    placeholder="1000"
                    required
                    aria-describedby="consumers_count_help"
                    class="{{ $inputClass }}"
                >

                <p id="consumers_count_help" class="{{ $helpClass }}">
                    Total number of consumers in the simulation.
                </p>

                @error('consumers_count')
                    <p class="{{ $errorClass }}">{{ $message }}</p>
                @enderror
            </div>

            {{-- Number of Periods --}}
            <div>
                <label for="periods_count" class="{{ $labelClass }}">
                    <span>Number of Periods</span>

                    <span class="group relative inline-flex">
                        <button
                            type="button"
                            class="{{ $tooltipButtonClass }}"
                            aria-label="Help for Number of Periods"
                        >
                            ?
                        </button>

                        <span class="{{ $tooltipPanelClass }}" role="tooltip">
                            Enter how many periods the simulation should run.
                        </span>
                    </span>
                </label>

                <input
                    type="number"
                    min="1"
                    step="1"
                    inputmode="numeric"
                    name="periods_count"
                    id="periods_count"
                    value="{{ old('periods_count', $scenarioModel->periods_count ?? '') }}"
                    placeholder="10"
                    required
                    aria-describedby="periods_count_help"
                    class="{{ $inputClass }}"
                >

                <p id="periods_count_help" class="{{ $helpClass }}">
                    Number of periods to simulate.
                </p>

                @error('periods_count')
                    <p class="{{ $errorClass }}">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </fieldset>
</div>
