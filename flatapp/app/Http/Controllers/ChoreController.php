<?php

namespace App\Http\Controllers;

use App\Models\Chore;
use App\Models\Membership;
use Illuminate\Http\Request;

class ChoreController extends Controller
{
    public function index()
    {
        $flatId = session('current_flat_id');

        $chores = Chore::query()
            ->where('flat_id', $flatId)
            ->with('assignee')
            ->latest()
            ->get();

        $members = Membership::query()
            ->where('flat_id', $flatId)
            ->where('status', 'active')
            ->with('user')
            ->get();

        return view('chores.index', [
            'chores' => $chores,
            'members' => $members,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $data['flat_id'] = session('current_flat_id');
        $data['status'] = 'pending';

        Chore::create($data);

        return redirect()->route('chores.index');
    }

    public function edit(Chore $chore)
    {
        abort_unless($chore->flat_id == session('current_flat_id'), 403);

        return view('chores.edit', ['chore' => $chore]);
    }

    public function update(Request $request, Chore $chore)
    {
        abort_unless($chore->flat_id == session('current_flat_id'), 403);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $chore->update($data);

        return redirect()->route('chores.index');
    }

    public function complete(Chore $chore)
    {
        abort_unless($chore->flat_id == session('current_flat_id'), 403);

        $chore->update(['status' => 'done']);

        return redirect()->route('chores.index');
    }

    public function destroy(Chore $chore)
    {
        abort_unless($chore->flat_id == session('current_flat_id'), 403);

        $chore->delete();

        return redirect()->route('chores.index');
    }
}
