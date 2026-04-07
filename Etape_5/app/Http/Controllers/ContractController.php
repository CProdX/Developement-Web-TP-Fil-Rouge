<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use Illuminate\View\View;

class ContractController extends Controller
{
    public function index(): View
    {
        $contracts = Contract::query()
            ->select(['id', 'client_id', 'label as name', 'heures_incluses as included_hours'])
            ->orderByDesc('id')
            ->get()
            ->toArray();

        $clients = Client::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get()
            ->toArray();

        return view('contracts.index', [
            'contracts' => $contracts,
            'clients' => $clients,
        ]);
    }
}
