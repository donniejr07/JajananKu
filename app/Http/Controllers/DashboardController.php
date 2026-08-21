<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, ReportService $reports, TransactionService $transactions): View
    {
        $month = $request->query('month')
            ? Carbon::parse($request->query('month'))->startOfMonth()
            : now()->startOfMonth();

        return view('dashboard', [
            'summary' => $reports->summary($request->user(), $month),
            'trend' => $reports->cashFlowTrend($request->user()),
            'recent' => $transactions->recent($request->user()),
            'selectedMonth' => $month,
        ]);
    }
}
