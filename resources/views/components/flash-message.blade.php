@props(['message'])

<div x-data="{ show: true }"
     x-show="show"
     x-transition
     x-init="setTimeout(() => show = false, 4000)"
     class="bg-secondary-container/40 border border-secondary text-on-secondary-container rounded-lg px-stack-md py-stack-sm flex items-center gap-stack-sm">
    <span class="material-symbols-outlined text-secondary">check_circle</span>
    <span class="font-body-md text-body-md">{{ $message }}</span>
    <button @click="show = false" class="ml-auto text-on-secondary-container/70 hover:text-on-secondary-container">
        <span class="material-symbols-outlined text-sm">close</span>
    </button>
</div>
