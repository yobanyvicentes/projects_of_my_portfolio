<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;

class ActivityController extends Controller
{
    public function index()
    {
        $flatId = session('current_flat_id');

        $items = ActivityLog::query()
            ->where('flat_id', $flatId)
            ->latest()
            ->limit(50)
            ->get();

        return view('activity.index', ['activities' => $items]);
    }
}
