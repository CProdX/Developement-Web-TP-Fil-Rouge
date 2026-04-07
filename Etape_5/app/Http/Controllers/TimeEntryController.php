<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\View\View;

class TimeEntryController extends Controller
{
    public function index(): View
    {
        $entries = TimeEntry::query()
            ->select(['id', 'ticket_id', 'user_id', 'duree_minutes', 'commentaire'])
            ->orderByDesc('id')
            ->get()
            ->map(fn (TimeEntry $entry): array => [
                'id' => $entry->id,
                'ticket_id' => $entry->ticket_id,
                'user_id' => $entry->user_id,
                'hours' => round(((int) $entry->duree_minutes) / 60, 2),
                'note' => $entry->commentaire,
            ])
            ->all();

        $tickets = Ticket::query()
            ->select(['id', 'sujet as title'])
            ->get()
            ->toArray();

        $users = User::query()
            ->select(['id', 'name'])
            ->get()
            ->toArray();

        return view('time-entries.index', [
            'entries' => $entries,
            'tickets' => $tickets,
            'users' => $users,
        ]);
    }
}
