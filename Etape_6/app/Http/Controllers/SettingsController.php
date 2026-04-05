<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function show(): View
    {
        return view('settings.show');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'lang' => ['required', 'in:fr,en'],
            'notif' => ['required', 'in:oui,non'],
        ]);

        return back()->with('success', 'Parametres enregistres en mode demo (non persistant).');
    }
}
