<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $query = Client::query()->with(['contracts', 'projects.tickets.timeEntries']);

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($subQuery) use ($search): void {
                $subQuery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $clients = $query->orderBy('name')->get();

        $clients = $clients->map(function (Client $client): Client {
            $includedBudget = (float) $client->contracts->sum('included_hours');
            $trackedTickets = $client->projects
                ->flatMap->tickets
                ->whereIn('status', ['En cours', 'Ferme']);

            $includedUsed = (float) $trackedTickets
                ->where('billing_type', 'inclus')
                ->sum('hours_spent');

            $facturable = (float) $trackedTickets
                ->where('billing_type', 'facturable')
                ->sum('hours_spent');

            $remaining = max($includedBudget - $includedUsed, 0);
            $overflow = max($includedUsed - $includedBudget, 0);

            $client->included_budget = $includedBudget;
            $client->hours_remaining = $remaining;
            $client->hours_to_bill = $facturable + $overflow;

            return $client;
        });

        return view('clients.index', [
            'clients' => $clients,
        ]);
    }
}
