<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ReportService
{
    public const SUMMARY_TAG = 'user-summary';

    public const CATEGORY_TAG = 'user-categories';

    public function summary(User $user, ?Carbon $month = null): array
    {
        $month ??= now();
        $key = "summary:{$user->id}:{$month->format('Y-m')}";
        $tags = [self::SUMMARY_TAG, "user:{$user->id}"];

        return Cache::tags($tags)->remember($key, now()->addHour(), function () use ($user, $month) {
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $income = (float) $user->transactions()
                ->where('type', 'income')
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->sum('amount');

            $expense = (float) $user->transactions()
                ->where('type', 'expense')
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->sum('amount');

            $totalIncome = (float) $user->transactions()->where('type', 'income')->sum('amount');
            $totalExpense = (float) $user->transactions()->where('type', 'expense')->sum('amount');

            return [
                'balance' => $totalIncome - $totalExpense,
                'income' => $income,
                'expense' => $expense,
            ];
        });
    }

    public function cashFlowTrend(User $user, int $months = 6): array
    {
        $labels = [];
        $income = [];
        $expense = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $labels[] = $month->format('M');
            $income[] = (float) $user->transactions()
                ->where('type', 'income')
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->sum('amount');
            $expense[] = (float) $user->transactions()
                ->where('type', 'expense')
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->sum('amount');
        }

        return [
            'labels' => $labels,
            'income' => $income,
            'expense' => $expense,
        ];
    }

    public function expenseBreakdown(User $user, Carbon $from, Carbon $to): Collection
    {
        $rows = $user->transactions()
            ->where('type', 'expense')
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->selectRaw('category_id, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->map(fn ($row) => [
                'category_id' => $row->category_id,
                'name' => $row->category->name,
                'icon' => $row->category->icon,
                'color' => $row->category->color,
                'count' => (int) $row->count,
                'total' => (float) $row->total,
            ]);

        return $rows->sortByDesc('total')->values();
    }

    public function categorySummary(User $user, Carbon $from, Carbon $to): Collection
    {
        $breakdown = $this->expenseBreakdown($user, $from, $to);
        $total = $breakdown->sum('total');

        return $breakdown->map(function ($row) use ($total) {
            $row['percentage'] = $total > 0 ? round(($row['total'] / $total) * 100, 1) : 0.0;

            return $row;
        });
    }

    public function incomeSummary(User $user, Carbon $from, Carbon $to): array
    {
        $totalIncome = (float) $user->transactions()
            ->where('type', 'income')
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->sum('amount');

        $totalExpense = (float) $user->transactions()
            ->where('type', 'expense')
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->sum('amount');

        return [
            'income' => $totalIncome,
            'expense' => $totalExpense,
            'net' => $totalIncome - $totalExpense,
        ];
    }

    public static function flushSummary(User $user): void
    {
        Cache::tags([self::SUMMARY_TAG, "user:{$user->id}"])->flush();
    }
}
