<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $userId = (int) $request->session()->get('user_id', 0);

        $user = User::query()
            ->whereKey($userId)
            ->select(['id', 'name', 'email'])
            ->first();

        if ($user === null) {
            $user = User::query()->select(['id', 'name', 'email'])->firstOrFail();
            $request->session()->put('user_id', $user->id);
        }

        return view('profile.show', [
            'user' => $user->toArray(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $userId = (int) $request->session()->get('user_id', 0);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email,' . $userId],
        ]);

        $user = User::query()->findOrFail($userId);
        $user->update($validated);

        return back()->with('success', 'Profil mis a jour avec succes.');
    }
}
