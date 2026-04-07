<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
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
            return back()->withInput()->with('error', 'Email ou mot de passe incorrect.');
        }

        // Vérifier le mot de passe : d'abord en clair (pour compatibilité étape 4), puis hashé
        $passwordIsValid = false;

        if ($user->password === $validated['password']) {
            // Mot de passe en clair (étape 4)
            $passwordIsValid = true;
        } else {
            // Vérifier si c'est un hash Bcrypt
            try {
                $passwordIsValid = Hash::check($validated['password'], $user->password);
            } catch (\Exception $e) {
                // Si Hash::check échoue, le mot de passe n'est pas bon
                $passwordIsValid = false;
            }
        }

        if (! $passwordIsValid) {
            return back()->withInput()->with('error', 'Email ou mot de passe incorrect.');
        }

        $request->session()->put('user_id', $user->id);

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
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'client',
            'lang' => 'fr',
            'notif' => 'oui',
        ]);

        $request->session()->put('user_id', $user->id);

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

        return back()->with('success', 'Fonctionnalite de reinitialisation non activee pour l instant.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('user_id');

        return to_route('login')->with('success', 'Deconnexion effectuee.');
    }
}
