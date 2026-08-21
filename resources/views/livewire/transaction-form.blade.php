<div>
    @if ($show)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="close"></div>

            <div class="relative bg-surface-container-lowest rounded-xl border border-outline-variant card-shadow w-full max-w-md p-stack-lg flex flex-col gap-stack-lg z-10 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between">
                    <h2 class="font-title-lg text-title-lg text-on-surface">
                        {{ $transactionId ? 'Edit Transaction' : 'Add Transaction' }}
                    </h2>
                    <button wire:click="close" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form wire:submit="save" class="flex flex-col gap-stack-md">
                    <div class="grid grid-cols-2 bg-surface-container-low rounded-lg p-1 border border-outline-variant">
                        <button type="button" wire:click="$set('type', 'expense')"
                            class="py-2 rounded-md font-body-md text-body-md transition-colors {{ $type === 'expense' ? 'bg-error text-on-error shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-highest' }}">
                            Expense
                        </button>
                        <button type="button" wire:click="$set('type', 'income')"
                            class="py-2 rounded-md font-body-md text-body-md transition-colors {{ $type === 'income' ? 'bg-secondary text-on-secondary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-highest' }}">
                            Income
                        </button>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="font-label-md text-label-md text-on-surface" for="amount">Amount</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant font-body-md">Rp</span>
                            <input wire:model="amount" id="amount" type="number" step="0.01" min="1"
                                placeholder="0"
                                class="form-input !pl-12" />
                        </div>
                        @error('amount') <span class="font-label-md text-label-md text-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="font-label-md text-label-md text-on-surface" for="categoryId">Category</label>
                        <div class="relative">
                            <select wire:model="categoryId" id="categoryId"
                                class="form-input !pl-10 appearance-none">
                                <option value="">Select category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px] pointer-events-none">category</span>
                        </div>
                        @error('categoryId') <span class="font-label-md text-label-md text-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="font-label-md text-label-md text-on-surface" for="date">Date</label>
                        <div class="relative" x-data="{}">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px] pointer-events-none">calendar_today</span>
                            <input wire:model="date" id="date" type="date"
                                onclick="this.showPicker()"
                                class="form-input cursor-pointer" />
                        </div>
                        @error('date') <span class="font-label-md text-label-md text-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="font-label-md text-label-md text-on-surface" for="note">Note</label>
                        <textarea wire:model="note" id="note" rows="3" placeholder="Optional note..."
                            class="w-full bg-surface-container-low border border-transparent rounded-lg px-4 py-2.5 font-body-md text-body-md text-on-surface placeholder:text-on-surface-variant/50 focus:bg-surface-container-lowest focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-all"></textarea>
                        @error('note') <span class="font-label-md text-label-md text-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-stack-sm mt-stack-sm">
                        <button type="button" wire:click="close"
                            class="px-stack-md py-2 rounded-lg border border-outline-variant font-body-md text-body-md text-on-surface-variant hover:bg-surface-container-low transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-primary text-on-primary px-stack-md py-2 rounded-lg font-body-md text-body-md hover:bg-on-primary-fixed-variant transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">check</span>
                            Save Transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
