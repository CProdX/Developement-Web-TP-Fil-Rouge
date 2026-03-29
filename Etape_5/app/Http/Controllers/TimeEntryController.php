<?php

namespace App\Http\Controllers;

use App\Support\FakeData;
use Illuminate\View\View;

class TimeEntryController extends Controller
{
    public function index(): View
    {
        return view('time-entries.index', [
            'entries' => FakeData::timeEntries(),
            'tickets' => FakeData::tickets(),
            'users' => FakeData::users(),
        ]);
    }
}
