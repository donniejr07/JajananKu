@extends('layouts.app')

@section('title', 'JajananKu - Income')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h3 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Income</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Track and manage your revenue streams.</p>
        </div>
        <button @click="Livewire.dispatch('open-transaction-form', { type: 'income' })"
            class="bg-primary text-on-primary font-body-md text-body-md py-2.5 px-5 rounded-lg shadow-sm hover:bg-on-primary-fixed-variant transition-all flex items-center gap-2 active:scale-95">
            <span class="material-symbols-outlined text-sm">add</span>
            Add Income
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-stack-md">
        <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-xl p-stack-md card-shadow card-hover transition-all flex flex-col gap-2">
            <div class="flex justify-between items-center text-on-surface-variant">
                <span class="font-body-md text-body-md">Total Income (This Month)</span>
                <span class="material-symbols-outlined text-secondary">trending_up</span>
            </div>
            <div class="font-headline-lg text-headline-lg text-on-surface text-secondary">+@rupiah($total)</div>
        </div>
    </div>

    <div class="flex flex-wrap gap-stack-sm items-center">
        <form method="GET" action="{{ route('transactions.income') }}" class="flex flex-wrap gap-stack-sm items-center flex-1">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-xl">search</span>
                <input name="search" value="{{ request('search') }}"
                    class="bg-surface-container-lowest border border-outline-variant rounded-lg pl-10 pr-4 py-2 font-body-md text-body-md text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none"
                    placeholder="Search income..." />
            </div>
            <input type="date" name="from" value="{{ request('from') }}"
                class="bg-surface-container-lowest border border-outline-variant rounded-lg px-3 py-2 font-body-md text-body-md text-on-surface focus:border-secondary outline-none" />
            <input type="date" name="to" value="{{ request('to') }}"
                class="bg-surface-container-lowest border border-outline-variant rounded-lg px-3 py-2 font-body-md text-body-md text-on-surface focus:border-secondary outline-none" />
            <button type="submit" class="bg-secondary text-on-secondary px-4 py-2 rounded-lg font-body-md text-body-md hover:brightness-90">Filter</button>
        </form>
    </div>

    @include('partials.transactions-table', ['transactions' => $transactions, 'type' => 'income'])
@endsection
