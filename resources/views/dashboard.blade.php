@extends('layouts.app')

@section('title', 'Dashboard - JajananKu')

@section('content')
    <div class="flex justify-between items-end flex-wrap gap-stack-md">
        <div>
            <h2 class="font-headline-lg text-headline-lg font-bold text-on-surface">Overview</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Your financial summary for {{ $selectedMonth->format('F Y') }}</p>
        </div>
        <form method="GET" action="{{ route('dashboard') }}" class="flex gap-2">
            <select name="month" onchange="this.form.submit()"
                class="bg-surface border border-outline-variant rounded-lg px-3 pr-10 py-1.5 font-body-md text-body-md text-on-surface focus:ring-2 focus:ring-secondary outline-none w-38">
                @foreach (['This Month' => now()->format('Y-m'), 'Last Month' => now()->subMonth()->format('Y-m')] as $label => $value)
                    <option value="{{ $value }}" {{ $selectedMonth->format('Y-m') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-stack-lg">
        <div class="bg-surface-container-lowest rounded-xl p-stack-md border border-outline-variant card-shadow card-hover transition-all flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary/5 rounded-full blur-xl pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-stack-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Total Balance</span>
                    <span class="material-symbols-outlined text-primary">account_balance</span>
                </div>
                <h3 class="font-headline-lg text-headline-lg text-on-surface font-bold">
                    @rupiah($summary['balance'])
                </h3>
            </div>
            <div class="mt-stack-md flex items-center gap-2 text-secondary font-label-md text-label-md">
                <span class="material-symbols-outlined text-sm">savings</span>
                <span>All-time balance</span>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl p-stack-md border border-outline-variant card-shadow card-hover transition-all flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-secondary/10 rounded-full blur-xl pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-stack-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Total Income</span>
                    <div class="w-8 h-8 rounded-full bg-secondary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-secondary text-sm">arrow_downward</span>
                    </div>
                </div>
                <h3 class="font-headline-lg text-headline-lg text-on-surface font-semibold text-secondary">
                    +@rupiah($summary['income'])
                </h3>
            </div>
            <div class="mt-stack-md w-full bg-surface-container h-1.5 rounded-full overflow-hidden">
                <div class="bg-secondary h-full" style="width: {{ $summary['balance'] > 0 ? min(100, round(($summary['income'] / max(1, $summary['balance'])) * 100)) : 0 }}%"></div>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl p-stack-md border border-outline-variant card-shadow card-hover transition-all flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-error/10 rounded-full blur-xl pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-stack-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Total Expense</span>
                    <div class="w-8 h-8 rounded-full bg-error/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-error text-sm">arrow_upward</span>
                    </div>
                </div>
                <h3 class="font-headline-lg text-headline-lg text-on-surface font-semibold text-error">
                    -@rupiah($summary['expense'])
                </h3>
            </div>
            <div class="mt-stack-md w-full bg-surface-container h-1.5 rounded-full overflow-hidden">
                <div class="bg-error h-full" style="width: {{ $summary['income'] > 0 ? min(100, round(($summary['expense'] / max(1, $summary['income'])) * 100)) : 0 }}%"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-stack-lg">
        <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl border border-outline-variant card-shadow flex flex-col">
            <div class="p-stack-md border-b border-outline-variant flex justify-between items-center">
                <h3 class="font-title-lg text-title-lg text-on-surface">Income vs Expense</h3>
            </div>
            <div class="p-stack-md flex-1 min-h-[300px] flex flex-col justify-end">
                @php
                    $max = max(max($trend['income']), max($trend['expense']), 1);
                @endphp
                <div class="flex items-end gap-4 flex-1">
                    @foreach ($trend['labels'] as $i => $label)
                        <div class="flex flex-col items-center gap-1 flex-1 group">
                            <div class="flex items-end gap-1 h-48">
                                <div class="w-4 bg-secondary rounded-t-sm group-hover:opacity-80 transition-opacity"
                                    style="height: {{ max(3, round(($trend['income'][$i] / $max) * 100)) }}%"></div>
                                <div class="w-4 bg-error rounded-t-sm group-hover:opacity-80 transition-opacity"
                                    style="height: {{ max(3, round(($trend['expense'][$i] / $max) * 100)) }}%"></div>
                            </div>
                            <span class="font-label-md text-[10px] text-on-surface-variant mt-2 {{ $loop->last ? 'font-bold text-on-surface' : '' }}">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="p-stack-sm flex justify-center gap-6 border-t border-outline-variant/30">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-sm bg-secondary"></div>
                    <span class="font-label-md text-label-md text-on-surface-variant">Income</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-sm bg-error"></div>
                    <span class="font-label-md text-label-md text-on-surface-variant">Expense</span>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant card-shadow flex flex-col h-[400px]">
            <div class="p-stack-md border-b border-outline-variant flex justify-between items-center">
                <h3 class="font-title-lg text-title-lg text-on-surface">Recent Transactions</h3>
                <a class="font-label-md text-label-md text-secondary hover:underline" href="{{ route('transactions.expenses') }}">View All</a>
            </div>
            <div class="flex-1 overflow-y-auto p-stack-md flex flex-col gap-4">
                @forelse ($recent as $transaction)
                    <div class="flex items-center justify-between group hover:bg-surface-container-low p-2 -mx-2 rounded-lg transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm {{ $transaction->type === 'income' ? 'bg-secondary/10 text-secondary' : 'bg-surface-container text-on-surface-variant' }}"
                                style="{{ $transaction->type === 'expense' ? '' : '' }}">
                                <span class="material-symbols-outlined text-sm">{{ $transaction->category?->icon ?? 'category' }}</span>
                            </div>
                            <div>
                                <p class="font-body-md text-body-md font-medium text-on-surface">{{ $transaction->note ?? $transaction->category?->name }}</p>
                                <p class="font-label-md text-label-md text-on-surface-variant">{{ $transaction->date->format('M d') }} • {{ $transaction->category?->name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-mono-data text-mono-data font-semibold {{ $transaction->type === 'income' ? 'text-secondary' : 'text-error' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }}@rupiah($transaction->amount)
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center text-center py-stack-lg text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl mb-2">receipt_long</span>
                        <p class="font-body-md text-body-md">No transactions yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
