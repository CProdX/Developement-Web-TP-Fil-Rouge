<?php

namespace App\Http\Controllers;

use App\Support\FakeData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('projects.index', [
            'projects' => FakeData::projects(),
            'clients' => FakeData::clients(),
        ]);
    }

    public function create(): View
    {
        return view('projects.create', [
            'clients' => FakeData::clients(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'client_id' => ['required', 'integer'],
            'status' => ['required', 'string'],
        ]);

        return to_route('projects.index')->with('success', 'Projet cree en mode demo (non persistant).');
    }

    public function show(int $id): View
    {
        $project = FakeData::findById(FakeData::projects(), $id);

        abort_unless($project !== null, 404);

        $projectTickets = array_values(array_filter(
            FakeData::tickets(),
            fn (array $ticket) => (int) $ticket['project_id'] === $project['id']
        ));

        return view('projects.show', [
            'project' => $project,
            'client' => FakeData::findById(FakeData::clients(), (int) $project['client_id']),
            'tickets' => $projectTickets,
        ]);
    }
}
