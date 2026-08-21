@extends('layouts.app')

@section('title', 'JajananKu - Expenses')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h3 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Expenses</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Track and manage your spending.</p>
        </div>
        <button @click="Livewire.dispatch('open-transaction-form', { type: 'expense' })"
            class="bg-primary text-on-primary font-body-md text-body-md py-2.5 px-5 rounded-lg shadow-sm hover:bg-on-primary-fixed-variant transition-all flex items-center gap-2 active:scale-95">
            <span class="material-symbols-outlined text-sm">add</span>
            Add Expense
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-stack-md">
        <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-xl p-stack-md card-shadow card-hover transition-all flex flex-col gap-2">
            <div class="flex justify-between items-center text-on-surface-variant">
                <span class="font-body-md text-body-md">Total Expenses (This Month)</span>
                <span class="material-symbols-outlined text-error">trending_up</span>
            </div>
            <div class="font-headline-lg text-headline-lg text-on-surface text-error">-@rupiah($total)</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-stack-lg">
        <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl border border-outline-variant card-shadow">
            <div class="p-stack-md border-b border-outline-variant flex justify-between items-center">
                <h3 class="font-title-lg text-title-lg text-on-surface">Recent Expenses</h3>
            </div>
            <div class="flex flex-wrap gap-stack-sm items-center px-stack-md pt-stack-md">
                <form method="GET" action="{{ route('transactions.expenses') }}" class="flex flex-wrap gap-stack-sm items-center flex-1">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-xl">search</span>
                        <input name="search" value="{{ request('search') }}"
                            class="bg-surface-container-low border border-outline-variant rounded-lg pl-10 pr-4 py-2 font-body-md text-body-md text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none"
                            placeholder="Search expenses..." />
                    </div>
                    <input type="date" name="from" value="{{ request('from') }}"
                        class="bg-surface-container-low border border-outline-variant rounded-lg px-3 py-2 font-body-md text-body-md text-on-surface focus:border-secondary outline-none" />
                    <input type="date" name="to" value="{{ request('to') }}"
                        class="bg-surface-container-low border border-outline-variant rounded-lg px-3 py-2 font-body-md text-body-md text-on-surface focus:border-secondary outline-none" />
                    <button type="submit" class="bg-secondary text-on-secondary px-4 py-2 rounded-lg font-body-md text-body-md hover:brightness-90">Filter</button>
                </form>
            </div>
            <div class="p-stack-md">
                @include('partials.transactions-table', ['transactions' => $transactions, 'type' => 'expense'])
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant card-shadow flex flex-col">
            <div class="p-stack-md border-b border-outline-variant">
                <h3 class="font-title-lg text-title-lg text-on-surface">Expense Distribution</h3>
            </div>
            <div class="p-stack-md flex flex-col items-center gap-stack-md">
                @php
                    $totalBreakdown = $breakdown->sum('total');
                    $conic = [];
                    $offset = 0;
                    foreach ($breakdown as $row) {
                        $pct = $totalBreakdown > 0 ? ($row['total'] / $totalBreakdown) * 100 : 0;
                        $conic[] = "{$row['color']} {$offset}% " . ($offset + $pct) . '%';
                        $offset += $pct;
                    }
                    $conicGradient = $conic ? implode(', ', $conic) : '#e4e2e4 0% 100%';
                @endphp

                <div class="relative w-44 h-44 rounded-full" style="background: conic-gradient({{ $conicGradient }});">
                    <div class="absolute inset-6 bg-surface-container-lowest rounded-full flex items-center justify-center">
                        <div class="text-center">
                            <p class="font-label-md text-label-md text-on-surface-variant">Total</p>
                            <p class="font-title-lg text-title-lg text-on-surface">@rupiah($totalBreakdown)</p>
                        </div>
                    </div>
                </div>

                <div class="w-full flex flex-col gap-stack-sm">
                    @forelse ($breakdown as $row)
                        @php $pct = $totalBreakdown > 0 ? round(($row['total'] / $totalBreakdown) * 100, 1) : 0; @endphp
                        <div class="flex items-center gap-stack-sm">
                            <span class="w-3 h-3 rounded-full shrink-0" style="background: {{ $row['color'] }}"></span>
                            <span class="font-body-md text-body-md text-on-surface flex-1 truncate">{{ $row['name'] }}</span>
                            <span class="font-label-md text-label-md text-on-surface-variant">{{ $pct }}%</span>
                        </div>
                    @empty
                        <p class="font-body-md text-body-md text-on-surface-variant text-center py-stack-md">No expenses yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
