<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@et.esiea.fr'],
            [
                'name' => 'Admin ESIEA',
                'password' => 'password123',
            ]
        );

        $collaborator = User::query()->updateOrCreate(
            ['email' => 'collab@et.esiea.fr'],
            [
                'name' => 'Collab ESIEA',
                'password' => 'password123',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'client@et.esiea.fr'],
            [
                'name' => 'Client ESIEA',
                'password' => 'password123',
            ]
        );

        $acme = Client::query()->updateOrCreate(
            ['name' => 'Acme'],
            ['email' => 'contact@acme.test']
        );

        $globex = Client::query()->updateOrCreate(
            ['name' => 'Globex'],
            ['email' => 'hello@globex.test']
        );

        $initech = Client::query()->updateOrCreate(
            ['name' => 'Initech'],
            ['email' => 'support@initech.test']
        );

        $contractAcme = Contract::query()->updateOrCreate(
            ['label' => 'Contrat Acme 2026'],
            ['client_id' => $acme->id, 'heures_incluses' => 20]
        );

        $contractGlobex = Contract::query()->updateOrCreate(
            ['label' => 'Contrat Globex 2026'],
            ['client_id' => $globex->id, 'heures_incluses' => 12]
        );

        $contractInitech = Contract::query()->updateOrCreate(
            ['label' => 'Contrat Initech 2026'],
            ['client_id' => $initech->id, 'heures_incluses' => 8]
        );

        $projectA = Project::query()->updateOrCreate(
            ['nom' => 'Portail client'],
            [
                'client_id' => $acme->id,
                'contrat_id' => $contractAcme->id,
                'statut' => 'En cours',
                'description' => 'Projet portail client pour le suivi des demandes.',
            ]
        );

        $projectB = Project::query()->updateOrCreate(
            ['nom' => 'Refonte CRM'],
            [
                'client_id' => $globex->id,
                'contrat_id' => $contractGlobex->id,
                'statut' => 'Planifie',
                'description' => 'Refonte du CRM interne.',
            ]
        );

        $projectC = Project::query()->updateOrCreate(
            ['nom' => 'API support'],
            [
                'client_id' => $initech->id,
                'contrat_id' => $contractInitech->id,
                'statut' => 'En cours',
                'description' => 'API de support et suivi des tickets.',
            ]
        );

        $ticketA = Ticket::query()->updateOrCreate(
            ['title' => 'Bug login mobile'],
            [
                'project_id' => $projectA->id,
                'status' => 'Ouvert',
                'priority' => 'Haute',
                'billing_type' => 'inclus',
                'description' => 'Le formulaire de connexion ne repond pas sur mobile.',
            ]
        );

        $ticketB = Ticket::query()->updateOrCreate(
            ['title' => 'Ajout export CSV'],
            [
                'project_id' => $projectB->id,
                'status' => 'En cours',
                'priority' => 'Moyenne',
                'billing_type' => 'facturable',
                'description' => 'Ajouter un export CSV dans le module rapports.',
            ]
        );

        $ticketC = Ticket::query()->updateOrCreate(
            ['title' => 'Erreur 500 endpoint /tickets'],
            [
                'project_id' => $projectC->id,
                'status' => 'Ferme',
                'priority' => 'Haute',
                'billing_type' => 'inclus',
                'description' => 'Corriger une erreur serveur sur endpoint tickets.',
            ]
        );

        TimeEntry::query()->updateOrCreate(
            ['ticket_id' => $ticketA->id, 'user_id' => $collaborator->id, 'hours' => 1.0, 'note' => 'Diagnostic initial'],
            []
        );

        TimeEntry::query()->updateOrCreate(
            ['ticket_id' => $ticketA->id, 'user_id' => $collaborator->id, 'hours' => 1.0, 'note' => 'Correctif responsive'],
            []
        );

        TimeEntry::query()->updateOrCreate(
            ['ticket_id' => $ticketB->id, 'user_id' => $admin->id, 'hours' => 3.5, 'note' => 'Implementation export'],
            []
        );

        TimeEntry::query()->updateOrCreate(
            ['ticket_id' => $ticketC->id, 'user_id' => $collaborator->id, 'hours' => 1.0, 'note' => 'Patch endpoint API'],
            []
        );

        $this->command?->info('Donnees de demonstration creees ou mises a jour.');
    }
}
