<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => $request->input('email', $request->input('identifier')),
        ]);

        $data = $request->validate([
            'email' => ['required', 'string', 'max:255'],
        ]);

        $user = $this->resolveUser($data['email']);

        if (! $user || blank($user->email)) {
            return redirect()->route('password.request')
                ->withErrors([
                    'email' => 'Akun tidak ditemukan atau belum memiliki email untuk reset password.',
                ])
                ->onlyInput('email');
        }

        $status = Password::sendResetLink([
            'email' => $user->email,
        ]);

        if ($status !== Password::RESET_LINK_SENT) {
            return redirect()->route('password.request')
                ->withErrors([
                    'email' => __($status),
                ])
                ->onlyInput('email');
        }

        return redirect()->route('password.request')->with('status', 'Tautan reset password sudah dikirim ke email terdaftar.');
    }

    private function resolveUser(string $identifier): ?User
    {
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return User::where('email', $identifier)->first();
        }

        if (! Schema::hasColumn('users', 'whatsapp')) {
            return null;
        }

        $wa = trim($identifier);
        $wa = preg_replace('/[^0-9+]/', '', $wa);

        if (preg_match('/^0[0-9]+$/', $wa)) {
            $wa = '+62' . substr($wa, 1);
        } elseif (preg_match('/^62[0-9]+$/', $wa)) {
            $wa = '+' . $wa;
        }

        return User::where('whatsapp', $wa)->first();
    }
}