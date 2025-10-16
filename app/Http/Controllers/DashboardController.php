<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Document;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        // **CRITICAL FIX for Goal Updates**
        // We use fresh() to get the latest User model state from the database.
        // Then, we use load('company') to ensure the company relationship on 
        // that fresh user model is also re-fetched from the database.
        $user = $user->fresh(); 
        $user->load('company');

        $company = $user->company; 

        if (!$company) {
            return redirect()->route('company.required')
                ->with('error', 'Please create or join a company first.');
        }

        $now = now();
        $companyId = $company->id;
        
        // ==========================================================
        // 1. Efficient Revenue Calculation (6 Months)
        // ==========================================================
        $startDate = $now->copy()->subMonths(5)->startOfMonth();
        $months = collect(range(5, 0))->map(fn($i) => $now->copy()->subMonths($i)->format('F'));

        // Single GROUP BY query for all 6 months' revenue
        $monthlyTotals = Document::where('company_id', $companyId)
            ->where('status', 'paid')
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
            ->select(
                DB::raw('SUM(total) as revenue'),
                DB::raw('MONTH(created_at) as month_num')
            )
            ->pluck('revenue', 'month_num');

        // Map the results back to the month names for the chart
        $revenues = $months->map(function ($monthName) use ($monthlyTotals) {
            $monthNum = date('n', strtotime($monthName)); // 1 to 12
            return $monthlyTotals->get($monthNum, 0); // Use 0 if the month has no revenue
        });

        // ==========================================================
        // 2. Stat Calculations (Uses existing totals for efficiency)
        // ==========================================================
        $thisMonthRevenue = $monthlyTotals->get($now->month, 0); // Get from grouped results
        
        // This still requires a separate query for last month's comparison, 
        // but we keep it separate for clarity in this refactor step.
        $lastMonthRevenue = Document::where('company_id', $companyId)
            ->where('status', 'paid')
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->whereYear('created_at', $now->copy()->subMonth()->year)
            ->sum('total');

        // Goal progress
        // This line now uses the guaranteed fresh $company data:
        $goal = $company->monthly_goal ?? 0;
        // NOTE: Changed goal progress to display 100% maximum for cleanliness
        $goalProgress = $goal > 0 ? round(($thisMonthRevenue / $goal) * 100, 1) : 0;
        $goalProgress = min($goalProgress, 100); // Caps displayed progress at 100%

        // Counts (These COUNT() queries are fast)
        $productCount = Product::where('company_id', $companyId)->count();
        $clientCount = Client::where('company_id', $companyId)->count();
        $documentCount = Document::where('company_id', $companyId)->count();

        return view('dashboard', compact(
            'months', 'revenues',
            'thisMonthRevenue', 'lastMonthRevenue',
            'goal', 'goalProgress',
            'productCount', 'clientCount', 'documentCount'
        ));
    }
}
