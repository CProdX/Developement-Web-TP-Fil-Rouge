<?php

namespace App\Http\Controllers;

use App\Support\FakeData;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $tickets = FakeData::tickets();
        $projects = FakeData::projects();

        $stats = [
            'projects' => count($projects),
            'tickets' => count($tickets),
            'included' => count(array_filter($tickets, fn (array $t) => $t['billing_type'] === 'inclus')),
            'billable' => count(array_filter($tickets, fn (array $t) => $t['billing_type'] === 'facturable')),
        ];

        return view('dashboard', [
            'stats' => $stats,
            'tickets' => $tickets,
        ]);
    }
}
