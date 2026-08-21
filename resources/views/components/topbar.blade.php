<header class="bg-surface border-b border-outline-variant fixed top-0 right-0 w-full md:w-[calc(100%-280px)] h-16 flex justify-between items-center px-stack-lg z-30">
    <button class="md:hidden text-on-surface-variant p-2 rounded-lg hover:bg-surface-container-low transition-colors">
        <span class="material-symbols-outlined">menu</span>
    </button>

    <div class="relative w-full max-w-md hidden sm:block">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-xl">search</span>
        <input
            class="w-full bg-surface-container-low text-on-surface font-body-md text-body-md rounded-lg pl-10 pr-4 py-2 border border-transparent focus:border-secondary focus:bg-surface-container-lowest focus:ring-0 focus:outline-none transition-all placeholder:text-on-surface-variant/50"
            placeholder="Search transactions..."
            type="text" />
    </div>

    <div class="flex items-center gap-stack-sm">
        <button class="text-on-surface-variant p-2 rounded-lg hover:bg-surface-container-low transition-all relative">
            <span class="material-symbols-outlined">notifications</span>
            <span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full border border-surface"></span>
        </button>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-on-surface-variant p-2 rounded-lg hover:bg-surface-container-low transition-all" title="Log out">
                <span class="material-symbols-outlined">logout</span>
            </button>
        </form>

        <a href="{{ route('profile.edit') }}" class="w-8 h-8 rounded-full bg-surface-container-highest border border-outline-variant overflow-hidden flex items-center justify-center">
            <span class="material-symbols-outlined text-on-surface-variant text-base">person</span>
        </a>
    </div>
</header>
