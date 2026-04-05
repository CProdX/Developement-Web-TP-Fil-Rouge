<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::query()->with(['client', 'tickets.timeEntries']);

        if ($request->filled('client_id')) {
            $query->where('client_id', (int) $request->query('client_id'));
        }

        if ($request->filled('status')) {
            $query->where('statut', $request->query('status'));
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where('nom', 'like', '%' . $search . '%');
        }

        $projects = $query->latest('id')->get()->map(function (Project $project): Project {
            $trackedTickets = $project->tickets->whereIn('status', ['En cours', 'Ferme']);
            $project->hours_spent = round((float) $trackedTickets->sum('hours_spent'), 2);

            return $project;
        });

        return view('projects.index', [
            'projects' => $projects,
            'clients' => Client::query()->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('projects.create', [
            'clients' => Client::query()->orderBy('name')->get(),
            'contracts' => Contract::query()->with('client')->orderBy('label')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'contract_id' => ['nullable', 'integer', 'exists:contrats,id'],
            'status' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
        ]);

        Project::query()->create($validated);

        return to_route('projects.index')->with('success', 'Projet cree avec succes.');
    }

    public function show(int $id): View
    {
        $project = Project::query()
            ->with(['client', 'tickets' => fn ($query) => $query->with('timeEntries')->latest('id')])
            ->findOrFail($id);

        $trackedTickets = $project->tickets->whereIn('status', ['En cours', 'Ferme']);

        $includedHours = (float) $trackedTickets
            ->where('billing_type', 'inclus')
            ->sum('hours_spent');

        $billableHours = (float) $trackedTickets
            ->where('billing_type', 'facturable')
            ->sum('hours_spent');

        $project->hours_spent = round($includedHours + $billableHours, 2);
        $project->included_hours_spent = round($includedHours, 2);
        $project->billable_hours_spent = round($billableHours, 2);

        return view('projects.show', [
            'project' => $project,
            'client' => $project->client,
            'tickets' => $project->tickets,
        ]);
    }

    public function destroy(int $id): RedirectResponse
    {
        $project = Project::query()->findOrFail($id);
        $project->delete();

        return to_route('projects.index')->with('success', 'Projet supprime avec succes.');
    }
}
