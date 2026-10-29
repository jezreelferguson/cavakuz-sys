<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Customers fetched successfully.',
            'data' => Customer::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'unique:customers,email'],
            'location' => ['nullable', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
        ]);

        $customer = Customer::create($validated);

        return response()->json([
            'message' => 'Customer created successfully.',
            'data' => $customer,
        ], 201);
    }

    public function show(Customer $customer)
    {
        return response()->json([
            'data' => $customer,
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'phone_number' => ['sometimes', 'string', 'max:30'],
            'email' => ['sometimes', 'nullable', 'email', 'unique:customers,email,'.$customer->id],
            'location' => ['sometimes', 'nullable', 'string', 'max:255'],
            'company' => ['sometimes', 'nullable', 'string', 'max:255'],
        ]);

        $customer->update($validated);

        return response()->json([
            'message' => 'Customer updated successfully.',
            'data' => $customer->fresh(),
        ]);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json([
            'message' => 'Customer deleted successfully.',
        ]);
    }
}
