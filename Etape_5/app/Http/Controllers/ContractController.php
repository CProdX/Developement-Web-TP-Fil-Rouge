<?php

namespace App\Http\Controllers;

use App\Support\FakeData;
use Illuminate\View\View;

class ContractController extends Controller
{
    public function index(): View
    {
        return view('contracts.index', [
            'contracts' => FakeData::contracts(),
            'clients' => FakeData::clients(),
        ]);
    }
}
