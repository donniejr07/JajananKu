<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __invoke(Request $request, ReportService $reports): View
    {
        $range = $this->range($request);

        return view('reports', [
            'from' => $range['from'],
            'to' => $range['to'],
            'totals' => $reports->incomeSummary($request->user(), $range['from'], $range['to']),
            'trend' => $reports->cashFlowTrend($request->user()),
            'breakdown' => $reports->expenseBreakdown($request->user(), $range['from'], $range['to']),
            'categorySummary' => $reports->categorySummary($request->user(), $range['from'], $range['to']),
        ]);
    }

    public function export(Request $request, ReportService $reports): Response
    {
        $range = $this->range($request);
        $user = $request->user();

        $data = [
            'user' => $user,
            'from' => $range['from'],
            'to' => $range['to'],
            'totals' => $reports->incomeSummary($user, $range['from'], $range['to']),
            'breakdown' => $reports->expenseBreakdown($user, $range['from'], $range['to']),
            'categorySummary' => $reports->categorySummary($user, $range['from'], $range['to']),
        ];

        // ponytail: synchronous export fine for small user base; move to queued job if reports grow heavy.
        $pdf = Pdf::loadView('reports.pdf', $data);

        return $pdf->download("laporan-{$range['from']->format('Y-m-d')}-{$range['to']->format('Y-m-d')}.pdf");
    }

    protected function range(Request $request): array
    {
        $from = $request->query('from')
            ? Carbon::parse($request->query('from'))->startOfDay()
            : now()->startOfMonth();

        $to = $request->query('to')
            ? Carbon::parse($request->query('to'))->endOfDay()
            : now()->endOfMonth();

        if ($to->lt($from)) {
            $to = $from->copy()->endOfMonth();
        }

        return ['from' => $from, 'to' => $to];
    }
}
