<?php

namespace App\Http\Controllers;

use App\Support\FakeData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        return view('profile.show', [
            'user' => FakeData::users()[0],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email'],
        ]);

        return back()->with('success', 'Profil mis a jour en mode demo (non persistant).');
    }
}
