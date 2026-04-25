<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberProfileController extends Controller
{
    public function edit(Request $request)
    {
        $membership = $request->user()
            ->memberships()
            ->where('flat_id', session('current_flat_id'))
            ->where('status', 'active')
            ->with('profile')
            ->firstOrFail();

        return view('member-profile.edit', compact('membership'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'phone' => 'nullable|string|max:60',
            'bank_account_number' => 'nullable|string|max:120',
        ]);

        $membership = $request->user()
            ->memberships()
            ->where('flat_id', session('current_flat_id'))
            ->where('status', 'active')
            ->firstOrFail();

        $membership->profile()->updateOrCreate([], $data);

        return back()->with('success', 'Profile updated');
    }
}
