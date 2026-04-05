<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        abort_if($user === null, 401, 'Utilisateur non connecte.');

        return view('profile.show', [
            'user' => $user,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email'],
        ]);

        $user = $request->user();
        abort_if($user === null, 401, 'Utilisateur non connecte.');

        $user->update($validated);

        return back()->with('success', 'Profil mis a jour avec succes.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();
        abort_if($user === null, 401, 'Utilisateur non connecte.');

        $plainCurrentPassword = (string) $validated['current_password'];
        $storedPassword = (string) $user->password;

        $currentPasswordIsValid = false;
        if (hash_equals($storedPassword, $plainCurrentPassword)) {
            $currentPasswordIsValid = true;
        } else {
            try {
                $currentPasswordIsValid = Hash::check($plainCurrentPassword, $storedPassword);
            } catch (\RuntimeException) {
                $currentPasswordIsValid = false;
            }
        }

        if (! $currentPasswordIsValid) {
            throw ValidationException::withMessages([
                'current_password' => 'Le mot de passe actuel est incorrect.',
            ]);
        }

        $user->update([
            'password' => $validated['password'],
        ]);

        return back()->with('success', 'Mot de passe modifie avec succes.');
    }
}
