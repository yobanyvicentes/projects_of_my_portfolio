<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurrentFlatController extends Controller
{
    public function switch(Request $request)
    {
        $request->validate([
            'flat_id' => 'required|exists:flats,id'
        ]);

        $user = $request->user();

        $membership = $user->memberships()
            ->where('flat_id', $request->flat_id)
            ->where('status', 'active')
            ->first();

        if (! $membership) {
            abort(403);
        }

        session(['current_flat_id' => $request->flat_id]);

        return back();
    }
}
