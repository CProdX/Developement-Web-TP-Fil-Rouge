<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $query = Ticket::query()->with(['project', 'timeEntries']);

        if ($request->filled('project_id')) {
            $query->where('project_id', (int) $request->query('project_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', (string) $request->query('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', (string) $request->query('priority'));
        }

        if ($request->filled('billing_type')) {
            $query->where('billing_type', (string) $request->query('billing_type'));
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($subQuery) use ($search): void {
                $subQuery->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        return view('tickets.index', [
            'tickets' => $query->latest('id')->get(),
            'projects' => Project::query()->orderBy('nom')->get(),
        ]);
    }

    public function create(): View
    {
        return view('tickets.create', [
            'projects' => Project::query()->orderBy('nom')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'priority' => ['required', 'in:Basse,Moyenne,Haute'],
            'billing_type' => ['required', 'in:inclus,facturable'],
            'description' => ['required', 'string'],
        ]);

        Ticket::query()->create([
            'title' => $validated['title'],
            'project_id' => $validated['project_id'],
            'priority' => $validated['priority'],
            'billing_type' => $validated['billing_type'],
            'description' => $validated['description'],
            'status' => 'Ouvert',
        ]);

        return to_route('tickets.index')->with('success', 'Ticket cree avec succes.');
    }

    public function show(int $id): View
    {
        $ticket = Ticket::query()
            ->with(['project.client', 'timeEntries.user'])
            ->findOrFail($id);

        return view('tickets.show', [
            'ticket' => $ticket,
            'project' => $ticket->project,
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function edit(int $id): View
    {
        $ticket = Ticket::query()->findOrFail($id);

        return view('tickets.edit', [
            'ticket' => $ticket,
            'projects' => Project::query()->orderBy('nom')->get(),
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'status' => ['required', 'in:Ouvert,En cours,Ferme'],
            'priority' => ['required', 'in:Basse,Moyenne,Haute'],
            'billing_type' => ['required', 'in:inclus,facturable'],
            'description' => ['required', 'string'],
        ]);

        $ticket = Ticket::query()->findOrFail($id);
        $ticket->update($validated);

        return to_route('tickets.show', ['id' => $ticket->id])->with('success', 'Ticket mis a jour.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $ticket = Ticket::query()->findOrFail($id);
        $ticket->delete();

        return to_route('tickets.index')->with('success', 'Ticket supprime avec succes.');
    }
}
