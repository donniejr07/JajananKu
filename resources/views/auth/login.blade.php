@extends('layouts.guest')

@section('title', 'Login - JajananKu')

@section('content')
    <div class="w-[90%] max-w-[360px] md:w-full md:max-w-[420px] bg-surface-container-lowest border border-outline-variant rounded-xl card-shadow p-stack-md md:p-stack-lg flex flex-col gap-stack-md md:gap-stack-lg z-10 transition-shadow duration-300 hover:shadow-md hover:border-outline">
        <div class="flex flex-col items-center justify-center text-center space-y-stack-sm pt-stack-sm">
            <h1 class="font-display-lg text-display-lg text-primary tracking-tight">JajananKu</h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant">Secure access to your personal finances.</p>
        </div>

        @if ($errors->any())
            <div class="bg-error-container/40 border border-error text-on-error-container rounded-lg px-stack-md py-stack-sm">
                <ul class="list-disc list-inside font-label-md text-label-md space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="flex flex-col gap-stack-md">
            @csrf

            <div class="flex flex-col gap-1">
                <label class="font-label-md text-label-md text-on-surface" for="email">Email</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px] pointer-events-none">mail</span>
                    <input class="form-input" id="email" name="email" type="email" placeholder="john.doe@example.com" value="{{ old('email') }}" required autofocus autocomplete="username" />
                </div>
            </div>

            <div class="flex flex-col gap-1">
                <label class="font-label-md text-label-md text-on-surface" for="password">Password</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px] pointer-events-none">lock</span>
                    <input class="form-input" id="password" name="password" type="password" placeholder="••••••••" required autocomplete="current-password" />
                </div>
            </div>

            <div class="flex items-center justify-between mt-2 mb-1">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input class="w-4 h-4 rounded border-outline-variant text-secondary focus:ring-secondary" name="remember" type="checkbox" />
                    <span class="font-body-md text-body-md text-on-surface-variant group-hover:text-on-surface transition-colors">Remember me</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="font-label-md text-label-md text-secondary hover:text-on-secondary-container transition-colors underline-offset-4 hover:underline" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="w-full bg-primary text-on-primary font-title-lg text-title-lg py-3 rounded-lg hover:bg-on-primary-fixed-variant focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all flex justify-center items-center gap-2 mt-stack-sm shadow-sm active:scale-[0.98]">
                Sign In
                <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
            </button>
        </form>

        <div class="pt-stack-sm border-t border-outline-variant/30 text-center">
            <p class="font-body-md text-body-md text-on-surface-variant">
                Don't have an account?
                <a class="font-title-lg text-title-lg text-secondary hover:text-on-secondary-container transition-colors underline-offset-4 hover:underline ml-1" href="{{ route('register') }}">Register here</a>
            </p>
        </div>
    </div>
@endsection
