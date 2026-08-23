<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OtpController extends Controller
{
    public function show(): View|RedirectResponse
    {
        if (! session()->has('otp_code')) {
            return redirect()->route('register');
        }

        return view('auth.verify-otp');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate(['otp' => 'required|string|size:6']);

        if (! session()->has('otp_code')) {
            return redirect()->route('register');
        }

        if (now()->gt(session('otp_expires_at'))) {
            return back()->withErrors(['otp' => 'OTP code has expired. Please resend.']);
        }

        if ($request->otp !== session('otp_code')) {
            return back()->withErrors(['otp' => 'Invalid OTP code. Please try again.']);
        }

        $user = User::create([
            'name' => session('otp_name'),
            'email' => session('otp_email'),
            'password' => Hash::make(session('otp_password')),
            'email_verified_at' => now(),
        ]);

        session()->forget(['otp_code', 'otp_expires_at', 'otp_name', 'otp_email', 'otp_password']);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    public function resend(): RedirectResponse
    {
        if (! session()->has('otp_email')) {
            return redirect()->route('register');
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        session(['otp_code' => $code, 'otp_expires_at' => now()->addMinutes(10)]);

        Mail::to(session('otp_email'))->send(new OtpMail($code));

        return back()->with('status', 'A new OTP code has been sent to your email.');
    }
}
