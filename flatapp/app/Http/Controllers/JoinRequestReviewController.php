<?php

namespace App\Http\Controllers;

use App\Models\JoinRequest;
use App\Models\Membership;

class JoinRequestReviewController extends Controller
{
    public function index()
    {
        $flatId = session('current_flat_id');

        $items = JoinRequest::query()
            ->where('flat_id', $flatId)
            ->where('status', 'pending')
            ->with('user')
            ->latest()
            ->get();

        return view('join.requests', ['requests' => $items]);
    }

    public function accept(JoinRequest $joinRequest)
    {
        $joinRequest->update(['status' => 'approved']);
        ActivityLogger::log($joinRequest->flat_id, 'join.approved', 'approved');
        Membership::firstOrCreate(
            [
                'user_id' => $joinRequest->user_id,
                'flat_id' => $joinRequest->flat_id,
            ],
            [
                'role' => 'flatmate',
                'status' => 'active',
            ]
        );

        return redirect()->route('join.requests');
    }

    public function decline(JoinRequest $joinRequest)
    {
        $joinRequest->update(['status' => 'rejected']);
        ActivityLogger::log($joinRequest->flat_id, 'join.rejected', 'rejected');

        return redirect()->route('join.requests');
    }
}
