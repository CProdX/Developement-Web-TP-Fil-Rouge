<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketApiController extends Controller
{
    public function index(): JsonResponse
    {
        $tickets = Ticket::query()
            ->with('project')
            ->latest('id')
            ->get()
            ->map(fn (Ticket $ticket): array => $this->formatTicket($ticket))
            ->values();

        return response()->json([
            'message' => 'Tickets recuperes avec succes.',
            'data' => $tickets,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'priority' => ['required', 'in:Basse,Moyenne,Haute'],
            'billing_type' => ['required', 'in:inclus,facturable'],
            'description' => ['required', 'string'],
        ]);

        $ticket = Ticket::query()->create([
            'title' => $validated['title'],
            'project_id' => $validated['project_id'],
            'priority' => $validated['priority'],
            'billing_type' => $validated['billing_type'],
            'description' => $validated['description'],
            'status' => 'Ouvert',
        ]);

        $ticket->load('project');

        return response()->json([
            'message' => 'Ticket cree avec succes.',
            'data' => $this->formatTicket($ticket),
        ], 201);
    }

    private function formatTicket(Ticket $ticket): array
    {
        $ticket->loadMissing('project');

        return [
            'id' => $ticket->id,
            'title' => $ticket->title,
            'project_id' => $ticket->project_id,
            'project_name' => $ticket->project?->name,
            'status' => $ticket->status,
            'priority' => $ticket->priority,
            'billing_type' => $ticket->billing_type,
            'description' => $ticket->description,
            'hours_spent' => round((float) $ticket->hours_spent, 2),
            'created_at' => optional($ticket->created_at)->format('d/m/Y H:i'),
        ];
    }
}

