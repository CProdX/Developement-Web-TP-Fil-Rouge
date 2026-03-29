<?php

namespace App\Http\Controllers;

use App\Support\FakeData;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        return view('clients.index', [
            'clients' => FakeData::clients(),
            'contracts' => FakeData::contracts(),
        ]);
    }
}
