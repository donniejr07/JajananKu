@php
    $isIncome = ($type ?? 'expense') === 'income';
    $accent = $isIncome ? 'text-secondary' : 'text-error';
@endphp

<div class="bg-surface-container-lowest rounded-xl border border-outline-variant card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low border-b border-outline-variant">
                <tr>
                    <th class="px-stack-md py-stack-sm font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Date</th>
                    <th class="px-stack-md py-stack-sm font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Description</th>
                    <th class="px-stack-md py-stack-sm font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Category</th>
                    <th class="px-stack-md py-stack-sm font-label-md text-label-md text-on-surface-variant uppercase tracking-wider text-right">Amount</th>
                    <th class="px-stack-md py-stack-sm"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/30">
                @forelse ($transactions as $transaction)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="px-stack-md py-stack-sm font-body-md text-body-md text-on-surface-variant whitespace-nowrap">
                            {{ $transaction->date->format('M d, Y') }}
                        </td>
                        <td class="px-stack-md py-stack-sm font-body-md text-body-md text-on-surface">
                            {{ $transaction->note ?? $transaction->category?->name }}
                        </td>
                        <td class="px-stack-md py-stack-sm">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full font-label-md text-label-md"
                                style="background: {{ $transaction->category?->color ?? '#e4e2e4' }}20; color: {{ $transaction->category?->color ?? '#45464d' }}">
                                <span class="material-symbols-outlined text-[14px]">{{ $transaction->category?->icon ?? 'category' }}</span>
                                {{ $transaction->category?->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="px-stack-md py-stack-sm text-right font-mono-data text-mono-data font-semibold whitespace-nowrap {{ $accent }}">
                            {{ $isIncome ? '+' : '-' }}@rupiah($transaction->amount)
                        </td>
                        <td class="px-stack-md py-stack-sm">
                            <div class="flex gap-1 justify-end opacity-0 group-hover:opacity-100 hover:opacity-100 transition-opacity">
                                <button @click="Livewire.dispatch('open-transaction-form', { id: {{ $transaction->id }} })"
                                    class="text-on-surface-variant hover:text-secondary p-1" title="Edit">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                <form method="POST" action="{{ route('transactions.destroy', $transaction) }}"
                                    onsubmit="return confirm('Delete this transaction?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-on-surface-variant hover:text-error p-1" title="Delete">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-stack-md py-stack-lg text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl mb-2 block">receipt_long</span>
                            <span class="font-body-md text-body-md">No transactions found.</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($transactions->hasPages())
        <div class="px-stack-md py-stack-sm border-t border-outline-variant flex items-center justify-between">
            <span class="font-label-md text-label-md text-on-surface-variant">
                Showing {{ $transactions->firstItem() ?? 0 }}-{{ $transactions->lastItem() ?? 0 }} of {{ $transactions->total() }}
            </span>
            <div class="flex gap-1">
                @if ($transactions->onFirstPage())
                    <span class="px-3 py-1.5 rounded-lg text-on-surface-variant/40 font-body-md text-body-md cursor-not-allowed">Prev</span>
                @else
                    <a href="{{ $transactions->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-outline-variant text-on-surface font-body-md text-body-md hover:bg-surface-container-low">Prev</a>
                @endif

                @if ($transactions->hasMorePages())
                    <a href="{{ $transactions->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-outline-variant text-on-surface font-body-md text-body-md hover:bg-surface-container-low">Next</a>
                @else
                    <span class="px-3 py-1.5 rounded-lg text-on-surface-variant/40 font-body-md text-body-md cursor-not-allowed">Next</span>
                @endif
            </div>
        </div>
    @endif
</div>
