<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReceiptController extends Controller
{
    public function index()
    {
        $flatId = session('current_flat_id');

        $receipts = Receipt::where('flat_id', $flatId)
            ->latest()
            ->get();

        return view('receipts.index', ['receipts' => $receipts]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $path = $request->file('file')->store('receipts', 'public');

        Receipt::create([
            'flat_id' => session('current_flat_id'),
            'uploaded_by' => $request->user()->id,
            'title' => $data['title'],
            'amount' => $data['amount'] ?? null,
            'file_path' => $path,
            'file_type' => $request->file('file')->getClientOriginalExtension(),
        ]);

        return redirect()->route('receipts.index');
    }

    public function destroy(Receipt $receipt)
    {
        abort_unless($receipt->flat_id == session('current_flat_id'), 403);

        Storage::disk('public')->delete($receipt->file_path);

        $receipt->delete();

        return redirect()->route('receipts.index');
    }
}
