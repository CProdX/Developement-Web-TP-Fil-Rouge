<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TimeEntryController extends Controller
{
    public function index(Request $request): View
    {
        $query = TimeEntry::query()->with(['ticket', 'user']);

        if ($request->filled('ticket_id')) {
            $query->where('ticket_id', (int) $request->query('ticket_id'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->query('user_id'));
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where('note', 'like', '%' . $search . '%');
        }

        return view('time-entries.index', [
            'entries' => $query->latest('id')->get(),
            'tickets' => Ticket::query()->orderBy('title')->get(),
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, int $ticketId): RedirectResponse
    {
        $ticket = Ticket::query()->findOrFail($ticketId);

        $validated = $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'hours' => ['required', 'numeric', 'min:0.25', 'max:24'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $entry = new TimeEntry();
        $entry->ticket_id = $ticket->id;
        $entry->user_id = $validated['user_id'] ?? null;
        $entry->hours = (float) $validated['hours'];
        $entry->note = $validated['note'] ?? null;
        $entry->save();

        return to_route('tickets.show', ['id' => $ticket->id])->with('success', 'Temps passe ajoute avec succes.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $entry = TimeEntry::query()->findOrFail($id);
        $ticketId = $entry->ticket_id;
        $entry->delete();

        return to_route('tickets.show', ['id' => $ticketId])->with('success', 'Saisie de temps supprimee.');
    }
}
