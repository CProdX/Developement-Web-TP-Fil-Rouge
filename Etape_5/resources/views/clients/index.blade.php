@extends('layouts.app')

@section('content')
<div class="cadre">
    <h2 class="titre-formulaire">CLIENTS</h2>

    <table class="tableau-liste">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Heures incluses</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($clients as $client)
            @php($contract = collect($contracts)->firstWhere('client_id', $client['id']))
            <tr>
                <td>#{{ $client['id'] }}</td>
                <td>{{ $client['name'] }}</td>
                <td>{{ $client['email'] }}</td>
                <td>{{ $contract['included_hours'] ?? 0 }} h</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
