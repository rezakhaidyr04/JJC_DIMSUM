<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\NewAccountOwnerMail;
use App\Mail\RegistrationOtpMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'karyawan',
            'password' => Hash::make($request->password),
        ]);

        $otpCode = $this->generateOtpCode();
        $user->storeRegistrationOtp($otpCode);

        Mail::to($user->email)->send(new RegistrationOtpMail($user, $otpCode));
        $this->notifyOwners($user, $otpCode);

        return redirect()
            ->route('registration.verify.form', ['email' => $user->email])
            ->with('status', 'Akun berhasil dibuat. Cek email Anda untuk kode OTP sebelum login.');
    }

    private function generateOtpCode(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function notifyOwners(User $user, string $otpCode): void
    {
        $ownerEmails = User::query()
            ->where('role', 'owner')
            ->whereNotNull('email')
            ->pluck('email')
            ->filter()
            ->values();

        foreach ($ownerEmails as $ownerEmail) {
            Mail::to($ownerEmail)->send(new NewAccountOwnerMail($user, $otpCode));
        }
    }
}
