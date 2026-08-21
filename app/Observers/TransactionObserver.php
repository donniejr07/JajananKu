<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Services\ReportService;

class TransactionObserver
{
    public function created(Transaction $transaction): void
    {
        $this->invalidate($transaction);
    }

    public function updated(Transaction $transaction): void
    {
        $this->invalidate($transaction);
    }

    public function deleted(Transaction $transaction): void
    {
        $this->invalidate($transaction);
    }

    protected function invalidate(Transaction $transaction): void
    {
        ReportService::flushSummary($transaction->user);
        \Illuminate\Support\Facades\Cache::tags([ReportService::CATEGORY_TAG, "user:{$transaction->user_id}"])->flush();
    }
}
