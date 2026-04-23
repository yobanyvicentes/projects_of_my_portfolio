<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use App\Models\SimulationRun;
use App\Services\SimulationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SimulationController extends Controller
{
    public function run(Scenario $scenario, SimulationService $simulationService): RedirectResponse
    {
        abort_unless($scenario->user_id === auth()->id(), 403);

        try {
            $run = $simulationService->run($scenario);

            return redirect()
                ->route('simulations.results', $run)
                ->with('success', 'Simulation executed successfully.');
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'The simulation could not be executed. Please review the scenario values and try again.');
        }
    }

    public function results(SimulationRun $run): View|RedirectResponse
    {
        abort_unless($run->user_id === auth()->id(), 403);

        $run->load([
            'scenario',
            'results' => fn ($query) => $query->orderBy('period'),
        ]);

        $finalResult = $run->results->last();

        if (! $finalResult) {
            return redirect()
                ->route('scenarios.show', $run->scenario_id)
                ->with('warning', 'This simulation run does not contain results yet.');
        }

        return view('simulations.results', [
            'run' => $run,
            'scenario' => $run->scenario,
            'results' => $run->results,
            'finalResult' => $finalResult,
        ]);
    }

    public function reset(Scenario $scenario): RedirectResponse
    {
        abort_unless($scenario->user_id === auth()->id(), 403);

        $scenario->runs()->delete();

        return redirect()
            ->route('scenarios.show', $scenario)
            ->with('success', 'The scenario history was reset successfully.');
    }
}
