<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $identifier = $data['identifier'];
        $password = $data['password'];

        // If identifier looks like an email, try normal email login first
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $identifier, 'password' => $password];

            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();

                return redirect()->intended(route('dashboard'));
            }
        }

        // Otherwise or if email attempt failed, try login by WhatsApp number
        $user = null;
        if (Schema::hasColumn('users', 'whatsapp')) {
            // Normalize WhatsApp input: remove non-digits/+, convert leading 0 to +62, add + if starts with 62
            $wa = $identifier;
            $wa = trim($wa);
            $wa = preg_replace('/[^0-9+]/', '', $wa);

            if (preg_match('/^0[0-9]+$/', $wa)) {
                $wa = '+62' . substr($wa, 1);
            } elseif (preg_match('/^62[0-9]+$/', $wa)) {
                $wa = '+' . $wa;
            }

            $user = User::where('whatsapp', $wa)->first();
        }

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'identifier' => 'The provided credentials do not match our records.',
        ])->onlyInput('identifier');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
