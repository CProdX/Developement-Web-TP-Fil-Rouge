<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContractController extends Controller
{
    public function index(Request $request): View
    {
        $query = Contract::query()->with(['client', 'projects.tickets.timeEntries']);

        if ($request->filled('client_id')) {
            $query->where('client_id', (int) $request->query('client_id'));
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where('label', 'like', '%' . $search . '%');
        }

        $contracts = $query->orderBy('id')->get();

        $contracts = $contracts->map(function (Contract $contract): Contract {
            $trackedTickets = $contract->projects
                ->flatMap->tickets
                ->whereIn('status', ['En cours', 'Ferme']);

            $includedUsed = (float) $trackedTickets
                ->where('billing_type', 'inclus')
                ->sum('hours_spent');

            $facturable = (float) $trackedTickets
                ->where('billing_type', 'facturable')
                ->sum('hours_spent');

            $remaining = max((float) $contract->included_hours - $includedUsed, 0);
            $overflow = max($includedUsed - (float) $contract->included_hours, 0);

            $contract->hours_used = $includedUsed;
            $contract->hours_remaining = $remaining;
            $contract->hours_to_bill = $facturable + $overflow;

            return $contract;
        });

        return view('contracts.index', [
            'contracts' => $contracts,
            'clients' => Client::query()->orderBy('name')->get(),
        ]);
    }
}
