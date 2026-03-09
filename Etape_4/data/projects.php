<?php

function getInitialProjects()
{
    return [
        [
            'id' => 101,
            'nom' => 'TP Fil Rouge',
            'client' => 'ESIEA Corp',
            'heures_contrat' => 50,
            'heures_consommees' => 21.5,
            'description' => 'Application web complete de gestion de ticketing pour les equipes internes.',
            'statut' => 'Actif',
        ],
        [
            'id' => 102,
            'nom' => 'Refonte Base SQL',
            'client' => 'Laval Services',
            'heures_contrat' => 35,
            'heures_consommees' => 31.0,
            'description' => 'Optimisation SQL, monitoring des performances et corrections de requetes.',
            'statut' => 'Actif',
        ],
        [
            'id' => 103,
            'nom' => 'Portail Support Client',
            'client' => 'Nexa Habitat',
            'heures_contrat' => 20,
            'heures_consommees' => 8.75,
            'description' => 'Evolution front-office et qualite de service du support ticketing.',
            'statut' => 'En attente client',
        ],
    ];
}

