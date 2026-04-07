<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Ticket;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $projectsCount = Project::query()->count();

        $tickets = Ticket::query()->select(['type'])->get();

        $stats = [
            'projects' => $projectsCount,
            'tickets' => $tickets->count(),
            'included' => $tickets->where('type', 'Inclus')->count(),
            'billable' => $tickets->where('type', 'Facturable')->count(),
        ];

        return view('dashboard', [
            'stats' => $stats,
        ]);
    }
}
