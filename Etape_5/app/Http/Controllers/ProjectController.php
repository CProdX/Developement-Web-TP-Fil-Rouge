<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $statut = trim((string) $request->query('statut', 'tous'));

        $projectsQuery = Project::query()->with('client:id,name');

        if ($q !== '') {
            $projectsQuery->where(function ($query) use ($q): void {
                $query
                    ->where('nom', 'like', '%' . $q . '%')
                    ->orWhereHas('client', function ($clientQuery) use ($q): void {
                        $clientQuery->where('name', 'like', '%' . $q . '%');
                    });
            });
        }

        if ($statut !== 'tous') {
            $projectsQuery->where('statut', $statut);
        }

        $projects = $projectsQuery
            ->select(['id', 'client_id', 'nom as name', 'statut as status'])
            ->orderByDesc('id')
            ->get()
            ->toArray();

        $clients = Client::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get()
            ->toArray();

        return view('projects.index', [
            'projects' => $projects,
            'clients' => $clients,
            'q' => $q,
            'statut' => $statut,
        ]);
    }

    public function create(): View
    {
        $clients = Client::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get()
            ->toArray();

        return view('projects.create', [
            'clients' => $clients,
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

        Project::query()->create([
            'nom' => $validated['name'],
            'client_id' => $validated['client_id'],
            'contrat_id' => $validated['contract_id'] ?? null,
            'statut' => $validated['status'],
            'description' => $validated['description'] ?? '',
        ]);

        return to_route('projects.index')->with('success', 'Projet cree avec succes.');
    }

    public function show(int $id): View
    {
        $project = Project::query()
            ->whereKey($id)
            ->select(['id', 'client_id', 'nom as name', 'statut as status'])
            ->first();

        abort_unless($project !== null, 404);

        $tickets = Ticket::query()
            ->where('project_id', $id)
            ->select(['id', 'sujet as title'])
            ->orderByDesc('id')
            ->get()
            ->toArray();

        $client = Client::query()
            ->whereKey($project->client_id)
            ->select(['id', 'name'])
            ->first();

        return view('projects.show', [
            'project' => $project->toArray(),
            'client' => $client?->toArray(),
            'tickets' => $tickets,
        ]);
    }

    public function destroy(int $id): RedirectResponse
    {
        $project = Project::query()->findOrFail($id);
        $project->delete();

        return to_route('projects.index')->with('success', 'Projet supprime avec succes.');
    }
}
