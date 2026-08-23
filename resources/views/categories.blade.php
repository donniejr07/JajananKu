@extends('layouts.app')

@section('title', 'JajananKu - Categories')

@section('content')
    <div x-data="{
        show: false,
        editingId: null,
        name: '',
        type: 'expense',
        icon: 'category',
        color: '#006c49',
        iconOpen: false,
        icons: [
            {name: 'category', label: 'Default'},
            {name: 'restaurant', label: 'Food'},
            {name: 'shopping_cart', label: 'Shopping'},
            {name: 'directions_car', label: 'Transport'},
            {name: 'home', label: 'Housing'},
            {name: 'local_hospital', label: 'Health'},
            {name: 'school', label: 'Education'},
            {name: 'movie', label: 'Entertainment'},
            {name: 'flight', label: 'Travel'},
            {name: 'checkroom', label: 'Clothing'},
            {name: 'coffee', label: 'Coffee'},
            {name: 'local_gas_station', label: 'Gas'},
            {name: 'phone_android', label: 'Phone'},
            {name: 'wifi', label: 'Internet'},
            {name: 'bolt', label: 'Electric'},
            {name: 'water_drop', label: 'Water'},
            {name: 'fitness_center', label: 'Gym'},
            {name: 'sports_esports', label: 'Gaming'},
            {name: 'redeem', label: 'Gifts'},
            {name: 'receipt_long', label: 'Bills'},
            {name: 'payments', label: 'Salary'},
            {name: 'account_balance', label: 'Bank'},
            {name: 'work', label: 'Work'},
            {name: 'trending_up', label: 'Invest'},
            {name: 'savings', label: 'Savings'},
            {name: 'store', label: 'Business'},
            {name: 'laptop', label: 'Freelance'},
            {name: 'pets', label: 'Pets'},
            {name: 'star', label: 'Favorite'},
            {name: 'more_horiz', label: 'Other'},
        ],
        openNew() { this.reset(); this.show = true; },
        openEdit(c) {
            this.editingId = c.id; this.name = c.name; this.type = c.type;
            this.icon = c.icon; this.color = c.color; this.show = true;
        },
        reset() { this.editingId = null; this.name = ''; this.type = 'expense'; this.icon = 'category'; this.color = '#006c49'; this.iconOpen = false; }
    }">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-stack-lg gap-stack-md">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-primary">Manage Categories</h2>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Organize your income and expenses for better tracking.</p>
            </div>
            <button @click="openNew()"
                class="bg-primary text-on-primary py-stack-sm px-stack-lg rounded-lg font-title-lg text-title-lg flex items-center gap-2 hover:bg-on-primary-fixed-variant transition-colors">
                <span class="material-symbols-outlined filled-icon">add</span>
                New Category
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-stack-lg">
            @php
                $expenseCategories = $categories->where('type', 'expense');
                $incomeCategories = $categories->where('type', 'income');
            @endphp

            <section class="glass-card rounded-xl p-stack-md flex flex-col">
                <div class="border-b border-outline-variant pb-stack-sm mb-stack-md flex justify-between items-center">
                    <h3 class="font-title-lg text-title-lg text-primary flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-error inline-block"></span>
                        Expense Categories
                    </h3>
                    <span class="font-label-md text-label-md text-on-surface-variant bg-surface-container-low px-2 py-1 rounded">{{ $expenseCategories->count() }} Items</span>
                </div>
                <div class="flex flex-col gap-stack-sm">
                    @forelse ($expenseCategories as $category)
                        <div class="group relative flex items-center justify-between p-stack-md border border-outline-variant rounded-xl bg-surface-container-lowest hover:border-secondary-container transition-all card-shadow">
                            <div class="flex items-center gap-stack-md min-w-0">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0" style="background: {{ $category->color }}20; color: {{ $category->color }}">
                                    <span class="material-symbols-outlined text-2xl">{{ $category->icon }}</span>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-title-lg text-title-lg font-semibold text-primary truncate">{{ $category->name }}</h4>
                                    <p class="font-label-md text-label-md text-on-surface-variant mt-0.5">
                                        {{ $category->transactions_count ?? 0 }} Transaksi
                                    </p>
                                </div>
                            </div>
                            <div class="text-right flex items-center gap-3">
                                <div class="mr-1">
                                    <span class="font-mono-data text-mono-data font-bold text-error block">
                                        -@rupiah($category->transactions_sum ?? 0)
                                    </span>
                                </div>
                                <div class="flex gap-1">
                                    <button @click="openEdit({{ $category->toJson() }})" class="text-on-surface-variant hover:text-secondary p-1" title="Edit">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Delete this category? Related transactions will also be deleted.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-on-surface-variant hover:text-error p-1" title="Delete">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="font-body-md text-body-md text-on-surface-variant py-stack-md">No expense categories yet.</p>
                    @endforelse
                </div>
            </section>

            <section class="glass-card rounded-xl p-stack-md flex flex-col">
                <div class="border-b border-outline-variant pb-stack-sm mb-stack-md flex justify-between items-center">
                    <h3 class="font-title-lg text-title-lg text-primary flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-secondary-container inline-block"></span>
                        Income Categories
                    </h3>
                    <span class="font-label-md text-label-md text-on-surface-variant bg-surface-container-low px-2 py-1 rounded">{{ $incomeCategories->count() }} Items</span>
                </div>
                <div class="flex flex-col gap-stack-sm">
                    @forelse ($incomeCategories as $category)
                        <div class="group relative flex items-center justify-between p-stack-md border border-outline-variant rounded-xl bg-surface-container-lowest hover:border-secondary-container transition-all card-shadow">
                            <div class="flex items-center gap-stack-md min-w-0">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0" style="background: {{ $category->color }}20; color: {{ $category->color }}">
                                    <span class="material-symbols-outlined text-2xl">{{ $category->icon }}</span>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-title-lg text-title-lg font-semibold text-primary truncate">{{ $category->name }}</h4>
                                    <p class="font-label-md text-label-md text-on-surface-variant mt-0.5">
                                        {{ $category->transactions_count ?? 0 }} Transaksi
                                    </p>
                                </div>
                            </div>
                            <div class="text-right flex items-center gap-3">
                                <div class="mr-1">
                                    <span class="font-mono-data text-mono-data font-bold text-secondary block">
                                        +@rupiah($category->transactions_sum ?? 0)
                                    </span>
                                </div>
                                <div class="flex gap-1">
                                    <button @click="openEdit({{ $category->toJson() }})" class="text-on-surface-variant hover:text-secondary p-1" title="Edit">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Delete this category? Related transactions will also be deleted.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-on-surface-variant hover:text-error p-1" title="Delete">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="font-body-md text-body-md text-on-surface-variant py-stack-md">No income categories yet.</p>
                    @endforelse
                </div>
            </section>
        </div>

        <div x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="show = false"></div>
            <div class="relative bg-surface-container-lowest rounded-xl border border-outline-variant card-shadow w-full max-w-md p-stack-lg flex flex-col gap-stack-lg z-10">
                <div class="flex items-center justify-between">
                    <h2 class="font-title-lg text-title-lg text-on-surface" x-text="editingId ? 'Edit Category' : 'New Category'"></h2>
                    <button @click="show = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form method="POST" :action="editingId ? `/categories/${editingId}` : '{{ route('categories.store') }}'" class="flex flex-col gap-stack-md">
                    @csrf
                    <template x-if="editingId"><input type="hidden" name="_method" value="PUT"></template>

                    <div class="flex flex-col gap-1">
                        <label class="font-label-md text-label-md text-on-surface" for="name">Name</label>
                        <input x-model="name" id="name" name="name" type="text" required
                            class="form-input" placeholder="e.g. Food & Dining" />
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="font-label-md text-label-md text-on-surface" for="type">Type</label>
                        <select x-model="type" id="type" name="type"
                            class="form-input appearance-none">
                            <option value="expense">Expense</option>
                            <option value="income">Income</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-1 relative">
                        <label class="font-label-md text-label-md text-on-surface">Icon</label>
                        <input type="hidden" name="icon" :value="icon">
                        <button type="button" @click="iconOpen = !iconOpen"
                            class="form-input flex items-center gap-2 text-left">
                            <span class="material-symbols-outlined text-xl" x-text="icon"></span>
                            <span class="font-body-md text-body-md text-on-surface" x-text="icons.find(i => i.name === icon)?.label || icon"></span>
                            <span class="material-symbols-outlined text-on-surface-variant ml-auto text-[18px]">expand_more</span>
                        </button>
                        <div x-show="iconOpen" @click.outside="iconOpen = false" x-cloak
                            class="absolute top-full left-0 right-0 mt-1 bg-surface-container-lowest border border-outline-variant rounded-lg shadow-lg z-20 max-h-48 overflow-y-auto p-2 grid grid-cols-5 gap-1">
                            <template x-for="item in icons" :key="item.name">
                                <button type="button"
                                    @click="icon = item.name; iconOpen = false"
                                    class="flex flex-col items-center gap-0.5 p-2 rounded-lg hover:bg-surface-container-low transition-colors"
                                    :class="icon === item.name ? 'bg-secondary/10 text-secondary ring-1 ring-secondary' : 'text-on-surface-variant'">
                                    <span class="material-symbols-outlined text-xl" x-text="item.name"></span>
                                    <span class="text-[10px] truncate w-full text-center" x-text="item.label"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="font-label-md text-label-md text-on-surface" for="color">Color</label>
                        <input x-model="color" id="color" name="color" type="color"
                            class="w-full h-10 rounded-lg border border-outline-variant bg-surface-container-low cursor-pointer" />
                    </div>

                    <div class="flex justify-end gap-stack-sm mt-stack-sm">
                        <button type="button" @click="show = false"
                            class="px-stack-md py-2 rounded-lg border border-outline-variant font-body-md text-body-md text-on-surface-variant hover:bg-surface-container-low transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-primary text-on-primary px-stack-md py-2 rounded-lg font-body-md text-body-md hover:bg-on-primary-fixed-variant transition-colors">
                            Save Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
