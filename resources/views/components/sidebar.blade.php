@php
    $nav = [
        ['label' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'dashboard'],
        ['label' => 'Income', 'icon' => 'payments', 'route' => 'transactions.income'],
        ['label' => 'Expense', 'icon' => 'shopping_cart', 'route' => 'transactions.expenses'],
        ['label' => 'Categories', 'icon' => 'category', 'route' => 'categories.index'],
        ['label' => 'Reports', 'icon' => 'bar_chart', 'route' => 'reports.index'],
        ['label' => 'Profile', 'icon' => 'person', 'route' => 'profile.edit'],
    ];
@endphp

<aside class="hidden md:flex bg-primary-container fixed left-0 top-0 h-screen w-sidebar-width border-r border-outline-variant shadow-sm z-40" x-data="{}">
    <div class="flex flex-col h-full py-margin-page w-full">
        <div class="px-stack-lg mb-stack-lg flex items-center gap-stack-md">
            <div class="w-10 h-10 rounded-lg bg-secondary flex items-center justify-center">
                <span class="material-symbols-outlined text-on-secondary filled-icon">account_balance_wallet</span>
            </div>
            <div>
                <h1 class="font-headline-md text-headline-md font-bold text-on-primary tracking-tight">JajananKu</h1>
                <p class="font-label-md text-label-md text-on-primary-container">Personal Finance</p>
            </div>
        </div>

        <nav class="flex-1 flex flex-col gap-stack-sm px-stack-md py-stack-md overflow-y-auto">
            @foreach ($nav as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-stack-md px-stack-md py-stack-sm rounded-r-lg transition-all active:scale-95
                          {{ $active
                              ? 'border-l-4 border-secondary text-secondary-fixed bg-on-primary-fixed-variant/10'
                              : 'border-l-4 border-transparent text-on-primary-container/70 hover:text-on-primary-container hover:bg-on-primary-fixed-variant/5' }}">
                    <span class="material-symbols-outlined" style="{{ $active ? "font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;" : '' }}">{{ $item['icon'] }}</span>
                    <span class="font-body-md text-body-md {{ $active ? 'font-medium' : '' }}">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="px-stack-lg mt-auto">
            <button
                @click="Livewire.dispatch('open-transaction-form')"
                class="w-full bg-secondary text-on-secondary font-body-md text-body-md py-stack-sm rounded-lg hover:brightness-90 transition-all flex items-center justify-center gap-stack-sm active:scale-95">
                <span class="material-symbols-outlined text-sm">add</span>
                Add Transaction
            </button>
        </div>
    </div>
</aside>
