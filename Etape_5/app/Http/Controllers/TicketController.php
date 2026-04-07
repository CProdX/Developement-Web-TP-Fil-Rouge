<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $type = trim((string) $request->query('type', 'tous'));
        $projectFilter = (int) $request->query('project_id', 0);

        $ticketsQuery = Ticket::query()->with('project:id,nom');

        if ($q !== '') {
            $ticketsQuery->where(function ($query) use ($q): void {
                $query
                    ->where('sujet', 'like', '%' . $q . '%')
                    ->orWhere('id', 'like', '%' . $q . '%')
                    ->orWhereHas('project', function ($projectQuery) use ($q): void {
                        $projectQuery->where('nom', 'like', '%' . $q . '%');
                    });
            });
        }

        if ($type !== 'tous') {
            $ticketsQuery->where('type', $type);
        }

        if ($projectFilter > 0) {
            $ticketsQuery->where('project_id', $projectFilter);
        }

        $tickets = $ticketsQuery
            ->select([
                'id',
                'project_id',
                'sujet as title',
                'type',
                'priorite as priority',
                'statut as status',
                'description',
                'temps_minutes',
            ])
            ->orderByDesc('id')
            ->get()
            ->map(fn (Ticket $ticket): array => [
                'id' => $ticket->id,
                'project_id' => $ticket->project_id,
                'title' => $ticket->title,
                'billing_type' => strtolower($ticket->type) === 'inclus' ? 'inclus' : 'facturable',
                'priority' => $ticket->priority,
                'status' => $ticket->status,
                'description' => $ticket->description,
                'hours_spent' => round(((int) $ticket->temps_minutes) / 60, 2),
            ])
            ->all();

        $projects = Project::query()
            ->select(['id', 'nom as name'])
            ->orderBy('nom')
            ->get()
            ->toArray();

        return view('tickets.index', [
            'tickets' => $tickets,
            'projects' => $projects,
            'q' => $q,
            'type' => $type,
            'projectFilter' => $projectFilter,
        ]);
    }

    public function create(): View
    {
        $projects = Project::query()
            ->select(['id', 'nom as name'])
            ->orderBy('nom')
            ->get()
            ->toArray();

        return view('tickets.create', [
            'projects' => $projects,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'priority' => ['required', 'in:Basse,Moyenne,Critique'],
            'billing_type' => ['required', 'in:inclus,facturable'],
            'description' => ['required', 'string'],
            'status' => ['nullable', 'string', 'max:100'],
        ]);

        Ticket::query()->create([
            'project_id' => $validated['project_id'],
            'sujet' => $validated['title'],
            'type' => $validated['billing_type'] === 'inclus' ? 'Inclus' : 'Facturable',
            'priorite' => $validated['priority'],
            'statut' => $validated['status'] ?? 'Ouvert',
            'description' => $validated['description'],
            'temps_minutes' => 0,
            'created_by_user_id' => session('user_id'),
        ]);

        return to_route('tickets.index')->with('success', 'Ticket cree avec succes.');
    }

    public function show(int $id): View
    {
        $ticket = Ticket::query()
            ->whereKey($id)
            ->select([
                'id',
                'project_id',
                'sujet as title',
                'type',
                'priorite as priority',
                'statut as status',
                'description',
                'temps_minutes',
            ])
            ->first();

        abort_unless($ticket !== null, 404);

        $project = Project::query()
            ->whereKey($ticket->project_id)
            ->select(['id', 'nom as name'])
            ->first();

        return view('tickets.show', [
            'ticket' => [
                'id' => $ticket->id,
                'project_id' => $ticket->project_id,
                'title' => $ticket->title,
                'billing_type' => strtolower($ticket->type) === 'inclus' ? 'inclus' : 'facturable',
                'priority' => $ticket->priority,
                'status' => $ticket->status,
                'description' => $ticket->description,
                'hours_spent' => round(((int) $ticket->temps_minutes) / 60, 2),
            ],
            'project' => $project?->toArray(),
        ]);
    }

    public function edit(int $id): View
    {
        $ticket = Ticket::query()
            ->whereKey($id)
            ->select([
                'id',
                'project_id',
                'sujet as title',
                'type',
                'priorite as priority',
                'statut as status',
                'description',
            ])
            ->first();

        abort_unless($ticket !== null, 404);

        $projects = Project::query()
            ->select(['id', 'nom as name'])
            ->orderBy('nom')
            ->get()
            ->toArray();

        return view('tickets.edit', [
            'ticket' => [
                'id' => $ticket->id,
                'project_id' => $ticket->project_id,
                'title' => $ticket->title,
                'billing_type' => strtolower($ticket->type) === 'inclus' ? 'inclus' : 'facturable',
                'priority' => $ticket->priority,
                'status' => $ticket->status,
                'description' => $ticket->description,
            ],
            'projects' => $projects,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:100'],
            'priority' => ['required', 'in:Basse,Moyenne,Critique'],
            'billing_type' => ['required', 'in:inclus,facturable'],
            'description' => ['required', 'string'],
        ]);

        $ticket = Ticket::query()->findOrFail($id);

        $ticket->update([
            'sujet' => $validated['title'],
            'type' => $validated['billing_type'] === 'inclus' ? 'Inclus' : 'Facturable',
            'priorite' => $validated['priority'],
            'statut' => $validated['status'],
            'description' => $validated['description'],
        ]);

        return to_route('tickets.show', ['id' => $id])->with('success', 'Ticket mis a jour avec succes.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $ticket = Ticket::query()->findOrFail($id);
        $ticket->delete();

        return to_route('tickets.index')->with('success', 'Ticket supprime avec succes.');
    }
}
