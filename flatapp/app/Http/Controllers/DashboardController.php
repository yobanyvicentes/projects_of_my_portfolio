<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Chore;
use App\Models\JoinRequest;
use App\Models\Membership;
use App\Models\ShoppingItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $flatId = session('current_flat_id');

        if (! $flatId) {
            return view('dashboard', [
                'membersCount' => 0,
                'pendingRequestsCount' => 0,
                'pendingChoresCount' => 0,
                'shoppingItemsCount' => 0,
                'receiptsCount' => 0,
                'recentActivities' => collect(),
            ]);
        }

        return view('dashboard', [
            'membersCount' => Membership::where('flat_id', $flatId)->where('status', 'active')->count(),
            'pendingRequestsCount' => JoinRequest::where('flat_id', $flatId)->where('status', 'pending')->count(),
            'pendingChoresCount' => Chore::where('flat_id', $flatId)->where('status', 'pending')->count(),
            'shoppingItemsCount' => ShoppingItem::where('flat_id', $flatId)->where('status', 'pending')->count(),
            'receiptsCount' => Schema::hasTable('receipts') ? DB::table('receipts')->where('flat_id', $flatId)->count() : 0,
            'recentActivities' => ActivityLog::where('flat_id', $flatId)->latest()->limit(5)->get(),
        ]);
    }
}
