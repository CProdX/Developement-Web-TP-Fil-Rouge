<?php

namespace App\Http\Controllers;

use App\Support\FakeData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(): View
    {
        return view('tickets.index', [
            'tickets' => FakeData::tickets(),
            'projects' => FakeData::projects(),
        ]);
    }

    public function create(): View
    {
        return view('tickets.create', [
            'projects' => FakeData::projects(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'project_id' => ['required', 'integer'],
            'priority' => ['required', 'string'],
            'billing_type' => ['required', 'in:inclus,facturable'],
            'description' => ['required', 'string'],
        ]);

        return to_route('tickets.index')->with('success', 'Ticket cree en mode demo (non persistant).');
    }

    public function show(int $id): View
    {
        $ticket = FakeData::findById(FakeData::tickets(), $id);

        abort_unless($ticket !== null, 404);

        return view('tickets.show', [
            'ticket' => $ticket,
            'project' => FakeData::findById(FakeData::projects(), (int) $ticket['project_id']),
        ]);
    }

    public function edit(int $id): View
    {
        $ticket = FakeData::findById(FakeData::tickets(), $id);

        abort_unless($ticket !== null, 404);

        return view('tickets.edit', [
            'ticket' => $ticket,
            'projects' => FakeData::projects(),
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'status' => ['required', 'string'],
            'priority' => ['required', 'string'],
            'billing_type' => ['required', 'in:inclus,facturable'],
            'description' => ['required', 'string'],
        ]);

        return to_route('tickets.show', ['id' => $id])->with('success', 'Ticket mis a jour en mode demo (non persistant).');
    }
}
