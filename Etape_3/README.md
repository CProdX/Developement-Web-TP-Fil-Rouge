# Application de Gestion de Ticketing - Étape 3

## Démarrage

```bash
cd public
php -S localhost:8002
```

## Comptes de test

- admin@et.esiea.fr / admin123
- collab@et.esiea.fr / collab123
- client@et.esiea.fr / client123

## Fonctionnalités

- Authentification
- Gestion des projets
- Gestion des tickets
- Validation des formulaires
- Sessions PHP


## 🎯 Fonctionnalités

### Authentification
✅ Connexion / Déconnexion  
✅ Validation formulaires (JS + PHP)  
✅ Sessions sécurisées  
✅ Messages flash

### Projets
✅ Liste avec filtres  
✅ Création  
✅ Détail  
✅ Heures contrat/consommées

### Tickets
✅ Liste avec filtres  
✅ Création / Modification  
✅ Détail  
✅ Types : Inclus / Facturable  
✅ Priorités et statuts

---

## 🔒 Sécurité

- Protection XSS : `htmlspecialchars()` sur toutes les sorties
- Validation serveur : Tous les formulaires validés en PHP
- Pages protégées : `requireAuth()` sur pages privées
- Sessions sécurisées

---

## 💾 Données

**Sans base de données** (Étape 3) :
- Stockage en session PHP (`$_SESSION`)
- Réinitialisation à chaque fermeture navigateur
- Données initiales dans `data/`

---

## 📝 Choix techniques

- **PHP Procédural** : Fonctions uniquement, pas d'objets
- **Séparation** : Vues (public) / Logique (actions) / Helpers (includes)
- **Messages flash** : Temporaires (1 seconde), puis suppression auto

---

## 👨‍💻 Auteur

**Collou Christian-Didier KOUAKOU**  
ESIEA - Mars 2026


