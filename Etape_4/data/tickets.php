<?php

function getInitialTickets()
{
    return [
        [
            'id' => 8825,
            'sujet' => 'Erreur GitLab SSH',
            'type' => 'Inclus',
            'temps' => '02:30',
            'priorite' => 'Critique',
            'statut' => 'A valider',
            'project_id' => 101,
            'description' => 'Acces SSH refuse sur le depot GitLab du projet.',
        ],
        [
            'id' => 8826,
            'sujet' => 'Optimisation SQL Laval',
            'type' => 'Facturable',
            'temps' => '04:15',
            'priorite' => 'Moyenne',
            'statut' => 'En cours',
            'project_id' => 102,
            'description' => 'Requete lente sur la vue principale.',
        ],
        [
            'id' => 8827,
            'sujet' => 'Mise a jour JUnit 5',
            'type' => 'Inclus',
            'temps' => '01:00',
            'priorite' => 'Basse',
            'statut' => 'Nouveau',
            'project_id' => 101,
            'description' => 'Aligner la stack de tests avec la derniere version.',
        ],
        [
            'id' => 8828,
            'sujet' => 'Validation facture sprint 4',
            'type' => 'Facturable',
            'temps' => '03:20',
            'priorite' => 'Moyenne',
            'statut' => 'A valider',
            'project_id' => 103,
            'description' => 'Verifier le detail des heures supplementaires avec le client.',
        ],
    ];
}

