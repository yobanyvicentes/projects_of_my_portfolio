<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use App\Models\SimulationRun;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $scenarioCount = Scenario::where('user_id', $userId)->count();

        $completedRunsCount = SimulationRun::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        $latestRun = SimulationRun::with(['scenario', 'results'])
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->whereHas('results')
            ->orderByDesc('executed_at')
            ->orderByDesc('id')
            ->first();

        $recentScenarios = Scenario::with(['runs.results'])
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'scenarioCount',
            'completedRunsCount',
            'latestRun',
            'recentScenarios'
        ));
    }
}
