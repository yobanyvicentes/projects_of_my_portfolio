<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScenarioRequest;
use App\Http\Requests\UpdateScenarioRequest;
use App\Models\Scenario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ScenarioController extends Controller
{
    public function index(): View
    {
        $scenarios = Scenario::query()
            ->where('user_id', Auth::id())
            ->with(['runs.results'])
            ->latest()
            ->paginate(10);

        return view('scenario.index', compact('scenarios'));
    }

    public function create(): View
    {
        return view('scenario.create', [
            'scenario' => new Scenario(),
        ]);
    }

    public function store(StoreScenarioRequest $request): RedirectResponse
    {
        $scenario = Scenario::create([
            ...$request->validated(),
            'user_id' => Auth::id(),
            'is_example' => false,
        ]);

        return redirect()
            ->route('scenarios.show', $scenario)
            ->with('success', 'Scenario created successfully.');
    }

    public function show(Scenario $scenario): View
    {
        $this->authorizeScenario($scenario);

        $scenario->load([
            'runs' => fn ($query) => $query->latest(),
            'runs.results' => fn ($query) => $query->orderBy('period'),
        ]);

        $latestCompletedRun = $scenario->runs
            ->where('status', 'completed')
            ->sortByDesc(fn ($run) => $run->executed_at ?? $run->created_at)
            ->first();

        $latestCompletedResult = $latestCompletedRun?->results->last();

        return view('scenario.show', compact(
            'scenario',
            'latestCompletedRun',
            'latestCompletedResult'
        ));
    }

    public function edit(Scenario $scenario): View
    {
        $this->authorizeScenario($scenario);

        return view('scenario.edit', compact('scenario'));
    }

    public function update(UpdateScenarioRequest $request, Scenario $scenario): RedirectResponse
    {
        $this->authorizeScenario($scenario);

        $scenario->update($request->validated());

        return redirect()
            ->route('scenarios.show', $scenario)
            ->with('success', 'Scenario updated successfully.');
    }

    public function destroy(Scenario $scenario): RedirectResponse
    {
        $this->authorizeScenario($scenario);

        $scenario->delete();

        return redirect()
            ->route('scenarios.index')
            ->with('success', 'Scenario deleted successfully.');
    }

    protected function authorizeScenario(Scenario $scenario): void
    {
        abort_unless($scenario->user_id === Auth::id(), 403);
    }
}
