<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return to_route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();

        if ($user === null) {
            return back()->withInput()->with('error', 'Email ou mot de passe invalide.');
        }

        $plainPassword = (string) $validated['password'];
        $storedPassword = (string) $user->password;

        $passwordIsValid = false;
        if (hash_equals($storedPassword, $plainPassword)) {
            $passwordIsValid = true;
        } else {
            try {
                $passwordIsValid = Hash::check($plainPassword, $storedPassword);
            } catch (\RuntimeException) {
                $passwordIsValid = false;
            }
        }

        if (! $passwordIsValid) {
            return back()->withInput()->with('error', 'Email ou mot de passe invalide.');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return to_route('dashboard')->with('success', 'Connexion reussie.');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::query()->create($validated);

        Auth::login($user);
        $request->session()->regenerate();

        return to_route('dashboard')->with('success', 'Compte cree avec succes.');
    }

    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        return back()->with('success', 'Email de reinitialisation simule.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login')->with('success', 'Deconnexion effectuee.');
    }
}
