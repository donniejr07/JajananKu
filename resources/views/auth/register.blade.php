@extends('layouts.guest')

@section('title', 'Register - JajananKu')

@section('content')
    <main class="w-full max-w-md z-10">
        <div class="glass-card rounded-xl p-8">
            <div class="text-center mb-8">
                <h1 class="font-headline-lg text-headline-lg text-primary mb-2">JajananKu</h1>
                <p class="font-body-md text-body-md text-on-surface-variant">Create an account to manage your finances.</p>
            </div>

            @if ($errors->any())
                <div class="bg-error-container/40 border border-error text-on-error-container rounded-lg px-stack-md py-stack-sm mb-4">
                    <ul class="list-disc list-inside font-label-md text-label-md space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-2" for="name">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-outline">person</span>
                        </div>
                        <input class="form-input" id="name" name="name" type="text" placeholder="John Doe" value="{{ old('name') }}" required autofocus autocomplete="name" />
                    </div>
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-2" for="email">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-outline">mail</span>
                        </div>
                        <input class="form-input" id="email" name="email" type="email" placeholder="you@example.com" value="{{ old('email') }}" required autocomplete="username" />
                    </div>
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-2" for="password">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-outline">lock</span>
                        </div>
                        <input class="form-input" id="password" name="password" type="password" placeholder="••••••••" required autocomplete="new-password" />
                    </div>
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-2" for="password_confirmation">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-outline">lock_reset</span>
                        </div>
                        <input class="form-input" id="password_confirmation" name="password_confirmation" type="password" placeholder="••••••••" required autocomplete="new-password" />
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn-primary w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm font-title-lg text-title-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Create Account
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center">
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Already have an account?
                    <a class="font-medium text-primary hover:underline transition-colors" href="{{ route('login') }}">Sign in here</a>
                </p>
            </div>
        </div>
    </main>
@endsection
