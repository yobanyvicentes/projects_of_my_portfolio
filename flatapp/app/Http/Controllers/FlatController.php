<?php

namespace App\Http\Controllers;

use App\Models\Flat;
use App\Models\Membership;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FlatController extends Controller
{
    public function create()
    {
        return view('flats.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = $request->user();

        $flat = Flat::create([
            'name' => $request->name,
            'invite_code' => strtoupper(Str::random(6)),
            'created_by' => $user->id,
        ]);

        Membership::create([
            'user_id' => $user->id,
            'flat_id' => $flat->id,
            'role' => 'admin',
            'status' => 'active',
        ]);

        session(['current_flat_id' => $flat->id]);

        ActivityLogger::log($flat->id, 'flat.created', $user->name.' created the flat');

        return redirect()->route('dashboard');
    }
}
