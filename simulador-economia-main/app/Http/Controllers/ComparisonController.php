<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComparisonController extends Controller
{
    public function index(): View
    {
        $scenarios = Scenario::query()
            ->where('user_id', auth()->id())
            ->with(['runs.results'])
            ->latest()
            ->get();

        return view('compare.index', [
            'scenarios' => $scenarios,
            'comparisons' => collect(),
            'selectedScenarioIds' => [],
        ]);
    }

    public function compare(Request $request): View
    {
        $validated = $request->validate([
            'selected_scenarios' => ['required', 'array', 'min:2', 'max:3'],
            'selected_scenarios.*' => ['integer', 'exists:scenarios,id'],
        ], [
            'selected_scenarios.required' => 'Select at least two scenarios to compare.',
            'selected_scenarios.min' => 'Select at least two scenarios to compare.',
            'selected_scenarios.max' => 'You can compare up to three scenarios at a time.',
        ]);

        $selectedScenarioIds = array_values(array_unique($validated['selected_scenarios']));

        $scenarios = Scenario::query()
            ->where('user_id', auth()->id())
            ->with(['runs.results'])
            ->latest()
            ->get();

        $selectedScenarios = $scenarios->whereIn('id', $selectedScenarioIds)->values();

        $comparisons = $selectedScenarios->map(function (Scenario $scenario) {
            $latestCompletedRun = $scenario->runs
                ->where('status', 'completed')
                ->sortByDesc(fn ($run) => $run->executed_at ?? $run->created_at)
                ->first();

            $finalResult = $latestCompletedRun?->results->last();

            return [
                'scenario' => $scenario,
                'run' => $latestCompletedRun,
                'final_result' => $finalResult,
                'has_results' => (bool) $finalResult,
            ];
        });

        return view('compare.index', [
            'scenarios' => $scenarios,
            'comparisons' => $comparisons,
            'selectedScenarioIds' => $selectedScenarioIds,
        ]);
    }
}
