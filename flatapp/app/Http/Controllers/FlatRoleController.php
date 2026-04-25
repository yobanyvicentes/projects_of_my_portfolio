<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;

class FlatRoleController extends Controller
{
    public function index()
    {
        $flatId = session('current_flat_id');
        $memberships = Membership::where('flat_id', $flatId)->where('status', 'active')->with('user')->get();
        return view('roles.index', ['memberships' => $memberships]);
    }

    public function update(Request $request, Membership $membership)
    {
        $request->validate(['role' => 'required|in:admin,flatmate,landlord']);

        if ($membership->role === 'admin' && $request->role !== 'admin') {
            $count = Membership::where('flat_id', $membership->flat_id)->where('status', 'active')->where('role', 'admin')->count();
            if ($count <= 1) {
                return back()->withErrors(['role' => 'A flat must always have at least one admin.']);
            }
        }

        $membership->update(['role' => $request->role]);
        return back();
    }
}
