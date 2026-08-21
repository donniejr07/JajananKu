@extends('layouts.app')

@section('title', 'JajananKu - Profile')

@section('content')
    <div class="mb-stack-lg">
        <h2 class="font-headline-lg text-headline-lg text-primary">Profile</h2>
        <p class="font-body-md text-body-md text-on-surface-variant mt-1">Manage your account settings.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-stack-lg">
        <div class="glass-card rounded-xl p-stack-md flex flex-col items-center gap-stack-sm h-fit">
            <div class="w-20 h-20 rounded-full bg-primary-container flex items-center justify-center">
                <span class="material-symbols-outlined text-on-primary text-4xl">person</span>
            </div>
            <h3 class="font-title-lg text-title-lg text-primary">{{ $user->name }}</h3>
            <p class="font-body-md text-body-md text-on-surface-variant">{{ $user->email }}</p>

            <form method="POST" action="{{ route('logout') }}" class="mt-stack-md w-full">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 border border-outline-variant text-error py-2 rounded-lg font-body-md text-body-md hover:bg-error/5 transition-colors">
                    <span class="material-symbols-outlined text-sm">logout</span>
                    Sign Out
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 flex flex-col gap-stack-lg">
            <div class="glass-card rounded-xl p-stack-md">
                <h3 class="font-title-lg text-title-lg text-primary mb-stack-md">Personal Information</h3>
                <form method="POST" action="{{ route('profile.update') }}" class="flex flex-col gap-stack-md">
                    @csrf
                    @method('PATCH')

                    <div class="flex flex-col gap-1">
                        <label class="font-label-md text-label-md text-on-surface" for="name">Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                            class="form-input" />
                        @error('name') <span class="font-label-md text-label-md text-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="font-label-md text-label-md text-on-surface" for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                            class="form-input" />
                        @error('email') <span class="font-label-md text-label-md text-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-primary text-on-primary px-stack-md py-2 rounded-lg font-body-md text-body-md hover:bg-on-primary-fixed-variant transition-colors">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <div class="glass-card rounded-xl p-stack-md">
                <h3 class="font-title-lg text-title-lg text-primary mb-stack-md">Security</h3>
                <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-stack-md">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col gap-1">
                        <label class="font-label-md text-label-md text-on-surface" for="current_password">Current Password</label>
                        <input id="current_password" name="current_password" type="password" required
                            class="form-input" />
                        @error('current_password', 'updatePassword') <span class="font-label-md text-label-md text-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="font-label-md text-label-md text-on-surface" for="password">New Password</label>
                        <input id="password" name="password" type="password" required
                            class="form-input" />
                        @error('password', 'updatePassword') <span class="font-label-md text-label-md text-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="font-label-md text-label-md text-on-surface" for="password_confirmation">Confirm New Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="form-input" />
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-primary text-on-primary px-stack-md py-2 rounded-lg font-body-md text-body-md hover:bg-on-primary-fixed-variant transition-colors">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
