<?php

namespace App\Http\Controllers;

use App\Models\ShoppingItem;
use Illuminate\Http\Request;

class ShoppingItemController extends Controller
{
    public function index()
    {
        $flatId = session('current_flat_id');

        $items = ShoppingItem::where('flat_id', $flatId)->latest()->get();

        return view('shopping.index', ['items' => $items]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'nullable|string|max:50',
        ]);

        $data['flat_id'] = session('current_flat_id');
        $data['added_by'] = $request->user()->id;
        $data['status'] = 'pending';

        ShoppingItem::create($data);

        return redirect()->route('shopping.index');
    }

    public function complete(ShoppingItem $item)
    {
        $item->update(['status' => 'bought']);

        return redirect()->route('shopping.index');
    }
}
