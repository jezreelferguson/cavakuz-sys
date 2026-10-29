<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Sales fetched successfully.',
            'data' => Sale::with(['customer', 'items.inventory'])->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'payment_method' => ['required', 'in:cash,card,transfer,mobile_money'],
            'payment_status' => ['required', 'in:full_payment,half_payment,not_paid'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.inventory_id' => ['required', 'exists:inventory_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ]);

        $totalAmount = 0;
        $saleItems = [];

        foreach ($validated['items'] as $item) {
            $inventory = InventoryItem::findOrFail($item['inventory_id']);
            $unitPrice = (float) $inventory->unit_price;
            $quantity = (int) $item['quantity'];
            $lineTotal = $unitPrice * $quantity;

            $inventory->decrement('stock_quantity', $quantity);

            $saleItems[] = [
                'inventory_id' => $inventory->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $lineTotal,
            ];

            $totalAmount += $lineTotal;
        }

        $sale = Sale::create([
            'customer_id' => $validated['customer_id'],
            'payment_method' => $validated['payment_method'],
            'payment_status' => $validated['payment_status'],
            'transaction_date' => now(),
            'total_amount' => $totalAmount,
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($saleItems as $saleItem) {
            $sale->items()->create($saleItem + ['sale_id' => $sale->id]);
        }

        return response()->json([
            'message' => 'Sale created successfully.',
            'data' => $sale->load(['customer', 'items.inventory']),
        ], 201);
    }

    public function show(Sale $sale)
    {
        return response()->json([
            'data' => $sale->load(['customer', 'items.inventory']),
        ]);
    }

    public function receipt(Sale $sale)
    {
        $sale->load(['customer', 'items.inventory']);

        return response()->json([
            'message' => 'Receipt generated successfully.',
            'data' => [
                'receipt_number' => 'RCPT-'.$sale->id,
                'customer' => $sale->customer,
                'transaction_date' => $sale->transaction_date,
                'payment_method' => $sale->payment_method,
                'payment_status' => $sale->payment_status,
                'items' => $sale->items,
                'total_amount' => $sale->total_amount,
            ],
        ]);
    }
}
