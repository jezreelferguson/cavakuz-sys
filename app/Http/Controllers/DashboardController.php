<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Dashboard summary fetched successfully.',
            'data' => [
                'total_customers' => Customer::count(),
                'total_sales' => Sale::count(),
                'total_revenue' => (float) Sale::sum('total_amount'),
                'pending_payments' => Sale::where('payment_status', '!=', 'full_payment')->count(),
                'recent_sales' => Sale::with('customer')->latest()->take(5)->get(),
            ],
        ]);
    }

    public function adminDashboard()
    {
        return $this->index();
    }

    public function financialReport()
    {
        $report = Sale::select(
            DB::raw("strftime('%Y-%m', transaction_date) as month"),
            DB::raw('SUM(total_amount) as total_amount'),
            DB::raw('COUNT(*) as sales_count')
        )
            ->groupBy(DB::raw("strftime('%Y-%m', transaction_date)"))
            ->orderBy('month')
            ->get();

        return response()->json([
            'message' => 'Financial report generated successfully.',
            'data' => $report,
        ]);
    }
}
