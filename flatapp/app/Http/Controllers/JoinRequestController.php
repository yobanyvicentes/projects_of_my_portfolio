<?php

namespace App\Http\Controllers;

use App\Models\Flat;
use App\Models\JoinRequest;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class JoinRequestController extends Controller
{
    public function create()
    {
        return view('join.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $flat = Flat::where('invite_code', strtoupper($request->code))->first();

        if (! $flat) {
            return back()->withErrors(['code' => 'Invalid code']);
        }

        JoinRequest::create([
            'user_id' => $request->user()->id,
            'flat_id' => $flat->id,
        ]);

        ActivityLogger::log($flat->id, 'join.requested', $request->user()->name.' requested to join');

        return back()->with('success','Request sent');
    }
}
