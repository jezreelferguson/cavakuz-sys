<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Inventory fetched successfully.',
            'data' => InventoryItem::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $item = InventoryItem::create($validated);

        return response()->json([
            'message' => 'Inventory item created successfully.',
            'data' => $item,
        ], 201);
    }

    public function show(InventoryItem $inventory)
    {
        return response()->json([
            'data' => $inventory,
        ]);
    }

    public function update(Request $request, InventoryItem $inventory)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'category' => ['sometimes', 'nullable', 'string', 'max:100'],
            'unit_price' => ['sometimes', 'numeric', 'min:0'],
            'stock_quantity' => ['sometimes', 'integer', 'min:0'],
            'description' => ['sometimes', 'nullable', 'string'],
        ]);

        $inventory->update($validated);

        return response()->json([
            'message' => 'Inventory item updated successfully.',
            'data' => $inventory->fresh(),
        ]);
    }

    public function destroy(InventoryItem $inventory)
    {
        $inventory->delete();

        return response()->json([
            'message' => 'Inventory item deleted successfully.',
        ]);
    }
}
