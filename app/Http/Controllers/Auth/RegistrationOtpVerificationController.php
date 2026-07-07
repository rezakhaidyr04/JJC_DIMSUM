<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationOtpMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class RegistrationOtpVerificationController extends Controller
{
    public function show(Request $request): View
    {
        return view('auth.verify-registration', [
            'email' => $request->query('email', old('email')),
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'exists:users,email'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $user = User::where('email', $data['email'])->firstOrFail();

        if (! $user->registrationOtpMatches($data['otp'])) {
            return back()
                ->withErrors([
                    'otp' => 'Kode OTP tidak valid atau sudah kedaluwarsa.',
                ])
                ->withInput();
        }

        $user->markRegistrationOtpAsVerified();

        return redirect()->route('login')->with('status', 'Akun berhasil diverifikasi. Silakan login untuk masuk ke dashboard.');
    }

    public function resend(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $data['email'])->firstOrFail();

        if (! $user->hasPendingRegistrationOtp()) {
            return back()->withErrors([
                'email' => 'Akun ini sudah diverifikasi. Silakan login.',
            ]);
        }

        $otpCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->storeRegistrationOtp($otpCode);
        Mail::to($user->email)->send(new RegistrationOtpMail($user, $otpCode));

        return back()->with('status', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}