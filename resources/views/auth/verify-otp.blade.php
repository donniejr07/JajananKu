@extends('layouts.guest')

@section('title', 'Verify OTP - JajananKu')

@section('content')
    <div class="w-full max-w-md z-10">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-margin-page">
            <div class="text-center mb-stack-lg">
                <div class="mb-stack-md flex justify-center">
                    <div class="w-16 h-16 bg-surface-container rounded-full flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-4xl">lock_open</span>
                    </div>
                </div>
                <h1 class="font-headline-lg text-headline-lg text-primary mb-stack-sm">Verify Your Account</h1>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    We've sent a 6-digit code to your email address (<span class="font-mono-data text-mono-data font-semibold text-primary">{{ session('otp_email') }}</span>). Please enter it below to continue.
                </p>
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

            @if (session('status'))
                <div class="bg-secondary/10 border border-secondary text-secondary rounded-lg px-stack-md py-stack-sm mb-4 font-label-md text-label-md">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('otp.verify') }}" method="POST" id="otp-form" class="space-y-stack-lg" x-data="{
                digits: ['','','','','',''],
                submit() { if(this.digits.every(d => d !== '')) this.$refs.form.submit(); }
            }" x-ref="form">
                @csrf
                <input type="hidden" name="otp" :value="digits.join('')">

                <div class="flex justify-between gap-stack-sm">
                    <template x-for="(digit, index) in digits" :key="index">
                        <input type="text" maxlength="1" inputmode="numeric"
                            class="w-12 h-14 text-center font-headline-md text-headline-md bg-surface-container-low border border-outline-variant rounded-lg focus:bg-surface-container-lowest focus:border-secondary focus:ring-2 focus:ring-secondary focus:ring-opacity-50 transition-all outline-none text-primary"
                            :autofocus="index === 0"
                            x-model="digits[index]"
                            @input="if($event.target.value.length === 1 && index < 5) $event.target.nextElementSibling?.focus(); submit()"
                            @keydown.backspace="if($event.target.value === '' && index > 0) $event.target.previousElementSibling?.focus()"
                            @paste.prevent="
                                let p = $event.clipboardData.getData('text').replace(/\D/g,'').slice(0,6);
                                for(let i=0;i<p.length;i++) digits[i]=p[i];
                                submit();
                            " />
                    </template>
                </div>

                <button type="submit" class="w-full bg-secondary text-on-secondary font-title-lg text-title-lg py-3 rounded-lg hover:bg-on-secondary-container transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary">
                    Verify & Proceed
                </button>
            </form>

            <div class="mt-stack-lg text-center font-body-md text-body-md text-on-surface-variant" x-data="{
                seconds: 59,
                expired: false,
                init() {
                    let timer = setInterval(() => {
                        this.seconds--;
                        if(this.seconds <= 0) { this.expired = true; clearInterval(timer); }
                    }, 1000);
                }
            }">
                <p>Didn't receive the code?</p>
                <template x-if="!expired">
                    <button disabled class="mt-2 text-secondary font-semibold opacity-50 cursor-not-allowed">
                        Resend (available in <span x-text="'0:' + (seconds < 10 ? '0' : '') + seconds"></span>)
                    </button>
                </template>
                <template x-if="expired">
                    <form action="{{ route('otp.resend') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="mt-2 text-secondary font-semibold hover:text-on-secondary-container transition-colors">
                            Resend Code
                        </button>
                    </form>
                </template>
            </div>
        </div>

        <div class="mt-stack-lg text-center">
            <a class="inline-flex items-center text-on-surface-variant hover:text-primary transition-colors font-body-md text-body-md" href="{{ route('login') }}">
                <span class="material-symbols-outlined mr-2 text-lg">arrow_back</span>
                Back to Login
            </a>
        </div>
    </div>
@endsection
