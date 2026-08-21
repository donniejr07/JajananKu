@extends('layouts.app')

@section('title', 'JajananKu - Reports & Analytics')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h3 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Reports & Analytics</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Insight into your cash flow and spending.</p>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-stack-md bg-surface-container-lowest p-stack-md rounded-xl border border-outline-variant card-shadow">
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap items-center gap-stack-md flex-1">
            <div class="flex items-center gap-stack-sm bg-surface-container-lowest border border-outline-variant rounded-lg px-stack-md py-1.5">
                <span class="material-symbols-outlined text-on-surface-variant text-sm">calendar_today</span>
                <input type="date" name="from" value="{{ $from->format('Y-m-d') }}"
                    class="border-none bg-transparent font-body-md text-body-md text-on-surface focus:ring-0 outline-none" />
                <span class="font-body-md text-body-md text-on-surface-variant">—</span>
                <input type="date" name="to" value="{{ $to->format('Y-m-d') }}"
                    class="border-none bg-transparent font-body-md text-body-md text-on-surface focus:ring-0 outline-none" />
            </div>
            <button type="submit" class="bg-secondary text-on-secondary px-stack-md py-1.5 rounded-lg font-body-md text-body-md hover:brightness-90 transition-all">
                Apply
            </button>
        </form>

        <a href="{{ route('reports.export', ['from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}"
            class="bg-surface-container-lowest border border-outline-variant text-primary font-body-md text-body-md py-1.5 px-stack-md rounded-lg flex items-center gap-stack-sm hover:bg-surface-container-low hover:border-outline transition-all">
            <span class="material-symbols-outlined text-sm">download</span>
            Export PDF
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-stack-md">
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/50 p-stack-md card-shadow flex flex-col gap-1">
            <span class="font-label-md text-label-md text-on-surface-variant uppercase">Total Income</span>
            <span class="font-headline-md text-headline-md text-secondary">+@rupiah($totals['income'])</span>
        </div>
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/50 p-stack-md card-shadow flex flex-col gap-1">
            <span class="font-label-md text-label-md text-on-surface-variant uppercase">Total Expense</span>
            <span class="font-headline-md text-headline-md text-error">-@rupiah($totals['expense'])</span>
        </div>
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/50 p-stack-md card-shadow flex flex-col gap-1">
            <span class="font-label-md text-label-md text-on-surface-variant uppercase">Net</span>
            <span class="font-headline-md text-headline-md {{ $totals['net'] >= 0 ? 'text-secondary' : 'text-error' }}">
                {{ $totals['net'] >= 0 ? '+' : '' }}@rupiah($totals['net'])
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-stack-lg">
        <div class="md:col-span-8 bg-surface-container-lowest rounded-xl border border-outline-variant p-stack-md card-shadow flex flex-col h-[400px]">
            <div class="flex justify-between items-center mb-stack-md border-b border-outline-variant/50 pb-stack-sm">
                <h3 class="font-title-lg text-title-lg text-primary">Cash Flow Trend</h3>
                <div class="flex gap-stack-md">
                    <div class="flex items-center gap-stack-sm"><div class="w-3 h-3 rounded-full bg-secondary"></div><span class="font-label-md text-label-md text-on-surface-variant">Income</span></div>
                    <div class="flex items-center gap-stack-sm"><div class="w-3 h-3 rounded-full bg-error"></div><span class="font-label-md text-label-md text-on-surface-variant">Expense</span></div>
                </div>
            </div>
            <div class="flex-1 flex items-end gap-4">
                @php $max = max(max($trend['income']), max($trend['expense']), 1); @endphp
                @foreach ($trend['labels'] as $i => $label)
                    <div class="flex flex-col items-center gap-1 flex-1 group">
                        <div class="flex items-end gap-1 h-56 w-full justify-center">
                            <div class="w-6 bg-secondary rounded-t-sm group-hover:opacity-80 transition-opacity"
                                style="height: {{ max(3, round(($trend['income'][$i] / $max) * 100)) }}%"></div>
                            <div class="w-6 bg-error rounded-t-sm group-hover:opacity-80 transition-opacity"
                                style="height: {{ max(3, round(($trend['expense'][$i] / $max) * 100)) }}%"></div>
                        </div>
                        <span class="font-label-md text-[10px] text-on-surface-variant">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="md:col-span-4 bg-surface-container-lowest rounded-xl border border-outline-variant p-stack-md card-shadow flex flex-col">
            <div class="mb-stack-md border-b border-outline-variant/50 pb-stack-sm">
                <h3 class="font-title-lg text-title-lg text-primary">Expense Breakdown</h3>
            </div>
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
            <div class="flex flex-col items-center gap-stack-md">
                <div class="relative w-40 h-40 rounded-full" style="background: conic-gradient({{ $conicGradient }});">
                    <div class="absolute inset-6 bg-surface-container-lowest rounded-full flex items-center justify-center">
                        <div class="text-center">
                            <p class="font-label-md text-label-md text-on-surface-variant">Total</p>
                            <p class="font-title-lg text-title-lg text-on-surface">@rupiah($totalBreakdown)</p>
                        </div>
                    </div>
                </div>
                <div class="w-full flex flex-col gap-stack-sm max-h-48 overflow-y-auto">
                    @forelse ($breakdown as $row)
                        @php $pct = $totalBreakdown > 0 ? round(($row['total'] / $totalBreakdown) * 100, 1) : 0; @endphp
                        <div class="flex items-center gap-stack-sm">
                            <span class="w-3 h-3 rounded-full shrink-0" style="background: {{ $row['color'] }}"></span>
                            <span class="font-body-md text-body-md text-on-surface flex-1 truncate">{{ $row['name'] }}</span>
                            <span class="font-label-md text-label-md text-on-surface-variant">{{ $pct }}%</span>
                        </div>
                    @empty
                        <p class="font-body-md text-body-md text-on-surface-variant text-center py-stack-md">No data.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="p-stack-md border-b border-outline-variant">
            <h3 class="font-title-lg text-title-lg text-primary">Detailed Category Summary</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low border-b border-outline-variant">
                    <tr>
                        <th class="px-stack-md py-stack-sm font-label-md text-label-md text-on-surface-variant uppercase">Category</th>
                        <th class="px-stack-md py-stack-sm font-label-md text-label-md text-on-surface-variant uppercase text-right">Transactions</th>
                        <th class="px-stack-md py-stack-sm font-label-md text-label-md text-on-surface-variant uppercase text-right">Total Spent</th>
                        <th class="px-stack-md py-stack-sm font-label-md text-label-md text-on-surface-variant uppercase text-right">% of Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @forelse ($categorySummary as $row)
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-stack-md py-stack-sm">
                                <span class="inline-flex items-center gap-2">
                                    <span class="w-9 h-9 rounded flex items-center justify-center" style="background: {{ $row['color'] }}20; color: {{ $row['color'] }}">
                                        <span class="material-symbols-outlined text-sm">{{ $row['icon'] }}</span>
                                    </span>
                                    <span class="font-body-md text-body-md font-medium text-on-surface">{{ $row['name'] }}</span>
                                </span>
                            </td>
                            <td class="px-stack-md py-stack-sm text-right font-mono-data text-mono-data text-on-surface">{{ $row['count'] }}</td>
                            <td class="px-stack-md py-stack-sm text-right font-mono-data text-mono-data text-on-surface">@rupiah($row['total'])</td>
                            <td class="px-stack-md py-stack-sm text-right font-mono-data text-mono-data text-on-surface-variant">{{ $row['percentage'] }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-stack-md py-stack-lg text-center text-on-surface-variant font-body-md text-body-md">No expense data in this range.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
