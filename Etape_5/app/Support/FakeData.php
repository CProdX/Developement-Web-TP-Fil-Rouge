<?php

namespace App\Support;

final class FakeData
{
    public static function users(): array
    {
        return [
            ['id' => 1, 'name' => 'Admin ESIEA', 'email' => 'admin@et.esiea.fr', 'role' => 'admin'],
            ['id' => 2, 'name' => 'Collab ESIEA', 'email' => 'collab@et.esiea.fr', 'role' => 'collaborateur'],
            ['id' => 3, 'name' => 'Client ESIEA', 'email' => 'client@et.esiea.fr', 'role' => 'client'],
        ];
    }

    public static function clients(): array
    {
        return [
            ['id' => 1, 'name' => 'Acme', 'email' => 'contact@acme.test'],
            ['id' => 2, 'name' => 'Globex', 'email' => 'hello@globex.test'],
            ['id' => 3, 'name' => 'Initech', 'email' => 'support@initech.test'],
        ];
    }

    public static function contracts(): array
    {
        return [
            ['id' => 1, 'client_id' => 1, 'name' => 'Contrat Acme 2026', 'included_hours' => 20],
            ['id' => 2, 'client_id' => 2, 'name' => 'Contrat Globex 2026', 'included_hours' => 12],
            ['id' => 3, 'client_id' => 3, 'name' => 'Contrat Initech 2026', 'included_hours' => 8],
        ];
    }

    public static function projects(): array
    {
        return [
            ['id' => 1, 'name' => 'Portail client', 'client_id' => 1, 'status' => 'En cours'],
            ['id' => 2, 'name' => 'Refonte CRM', 'client_id' => 2, 'status' => 'Planifie'],
            ['id' => 3, 'name' => 'API support', 'client_id' => 3, 'status' => 'En cours'],
        ];
    }

    public static function tickets(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Bug login mobile',
                'project_id' => 1,
                'status' => 'Ouvert',
                'priority' => 'Haute',
                'billing_type' => 'inclus',
                'hours_spent' => 2.0,
                'description' => 'Le formulaire de connexion ne repond pas sur mobile.',
            ],
            [
                'id' => 2,
                'title' => 'Ajout export CSV',
                'project_id' => 2,
                'status' => 'En cours',
                'priority' => 'Moyenne',
                'billing_type' => 'facturable',
                'hours_spent' => 3.5,
                'description' => 'Ajouter un export CSV dans le module rapports.',
            ],
            [
                'id' => 3,
                'title' => 'Erreur 500 endpoint /tickets',
                'project_id' => 3,
                'status' => 'Ferme',
                'priority' => 'Haute',
                'billing_type' => 'inclus',
                'hours_spent' => 1.0,
                'description' => 'Corriger une erreur serveur sur endpoint tickets.',
            ],
        ];
    }

    public static function timeEntries(): array
    {
        return [
            ['id' => 1, 'ticket_id' => 1, 'user_id' => 2, 'hours' => 1.0, 'note' => 'Diagnostic initial'],
            ['id' => 2, 'ticket_id' => 1, 'user_id' => 2, 'hours' => 1.0, 'note' => 'Correctif responsive'],
            ['id' => 3, 'ticket_id' => 2, 'user_id' => 1, 'hours' => 3.5, 'note' => 'Implementation export'],
            ['id' => 4, 'ticket_id' => 3, 'user_id' => 2, 'hours' => 1.0, 'note' => 'Patch endpoint API'],
        ];
    }

    public static function findById(array $items, int $id): ?array
    {
        foreach ($items as $item) {
            if ((int) $item['id'] === $id) {
                return $item;
            }
        }

        return null;
    }
}

