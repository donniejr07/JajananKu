<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use App\Services\ReportService;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $transactions,
        protected ReportService $reports,
    ) {
    }

    public function income(Request $request): View
    {
        $filters = $request->only(['from', 'to', 'search']) + ['type' => 'income'];
        $month = now()->startOfMonth();

        return view('income', [
            'transactions' => $this->transactions->list($request->user(), $filters),
            'total' => (float) $request->user()->transactions()
                ->where('type', 'income')
                ->whereBetween('date', [$month->copy()->startOfMonth()->toDateString(), $month->copy()->endOfMonth()->toDateString()])
                ->sum('amount'),
        ]);
    }

    public function expenses(Request $request): View
    {
        $filters = $request->only(['from', 'to', 'search']) + ['type' => 'expense'];
        $month = now()->startOfMonth();

        return view('expenses', [
            'transactions' => $this->transactions->list($request->user(), $filters),
            'total' => (float) $request->user()->transactions()
                ->where('type', 'expense')
                ->whereBetween('date', [$month->copy()->startOfMonth()->toDateString(), $month->copy()->endOfMonth()->toDateString()])
                ->sum('amount'),
            'breakdown' => $this->reports->expenseBreakdown(
                $request->user(),
                $month->copy()->startOfMonth(),
                $month->copy()->endOfMonth()
            ),
        ]);
    }

    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        $this->transactions->create($request->user(), $request->validated());

        return back()->with('status', 'Transaksi berhasil ditambahkan.');
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        abort_unless($transaction->user_id === $request->user()->id, 403);

        $this->transactions->update($transaction, $request->validated());

        return back()->with('status', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Request $request, Transaction $transaction): RedirectResponse
    {
        abort_unless($transaction->user_id === $request->user()->id, 403);

        $this->transactions->delete($transaction);

        return back()->with('status', 'Transaksi berhasil dihapus.');
    }
}
