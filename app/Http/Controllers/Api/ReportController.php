<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function summary(Request $request, ReportService $reports): JsonResponse
    {
        $month = $request->query('month')
            ? Carbon::parse($request->query('month'))->startOfMonth()
            : now()->startOfMonth();

        return response()->json($reports->summary($request->user(), $month));
    }

    public function breakdown(Request $request, ReportService $reports): JsonResponse
    {
        $from = $request->query('from')
            ? Carbon::parse($request->query('from'))->startOfDay()
            : now()->startOfMonth();

        $to = $request->query('to')
            ? Carbon::parse($request->query('to'))->endOfDay()
            : now()->endOfMonth();

        return response()->json([
            'totals' => $reports->incomeSummary($request->user(), $from, $to),
            'trend' => $reports->cashFlowTrend($request->user()),
            'breakdown' => $reports->expenseBreakdown($request->user(), $from, $to),
            'category_summary' => $reports->categorySummary($request->user(), $from, $to),
        ]);
    }
}
