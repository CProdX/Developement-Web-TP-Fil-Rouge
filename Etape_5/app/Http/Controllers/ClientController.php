<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = Client::query()
            ->select(['id', 'name', 'email'])
            ->orderBy('name')
            ->get()
            ->toArray();

        $contracts = Contract::query()
            ->select(['id', 'client_id', 'heures_incluses'])
            ->get()
            ->map(fn (Contract $contract): array => [
                'id' => $contract->id,
                'client_id' => $contract->client_id,
                'included_hours' => $contract->heures_incluses,
            ])
            ->all();

        return view('clients.index', [
            'clients' => $clients,
            'contracts' => $contracts,
        ]);
    }
}
