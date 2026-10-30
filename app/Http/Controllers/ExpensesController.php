<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expenses;

class ExpensesController extends Controller
{
    public function index()
    {
        return response()->json([
            "message" => "Expenses fetched successfully",
            "data" => Expenses::all(),
            "code" => 200,
        ]);
    }

    public function show(Expenses $Expenses)
    {
        return response()->json([
            "message" => "Expense Fetched Successfully",
            "data" => $Expenses,
            "code" => 200
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "total" => "required|numeric"
        ]);

        $expense = Expenses::create($validated);
        return response()->json([
            "message" => "Expenses Created Successfully",
            "data" => $expense,
            "code" => 201
        ]);
    }

    public function update(Request $request, Expenses $Expenses)
    {
        $validated = $request->validate([
            "name" => "sometimes|required",
            "total" => "sometimes|requires"
        ]);

        $Expenses->update($validated);

        return response()->json([
            "message" => "Expense Updated Successfully",
            "data" => $Expenses,
            "code" => 200
        ]);
    }



    public function destroy(Expenses $Expenses)
    {
        $Expenses->delete();
    }
}
