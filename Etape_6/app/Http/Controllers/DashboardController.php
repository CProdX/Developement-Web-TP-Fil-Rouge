<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Ticket;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $tickets = Ticket::query()->with('timeEntries')->latest('id')->limit(10)->get();
        $projects = Project::query()->count();

        $stats = [
            'projects' => $projects,
            'tickets' => Ticket::query()->count(),
            'included' => Ticket::query()->where('billing_type', 'inclus')->count(),
            'billable' => Ticket::query()->where('billing_type', 'facturable')->count(),
        ];

        return view('dashboard', [
            'stats' => $stats,
            'tickets' => $tickets,
        ]);
    }
}
