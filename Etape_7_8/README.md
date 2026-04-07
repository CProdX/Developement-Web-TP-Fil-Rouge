# ÉTAPE 7 & 8 — Finalisation du projet Laravel

Ce dossier sert de **synthèse finale** pour terminer le projet à partir de l’application Laravel de l’**Étape 6**.

L’objectif est de garder une base propre, simple et conforme aux consignes :
- une **API REST minimale** pour les tickets,
- une consommation de cette API en **JavaScript avec `fetch()`**,
- des améliorations de **sécurité**, **UX** et **documentation**,
- un projet final **livrable**, clair et cohérent.

---

## 1. Base du projet

Le projet de départ est l’application Laravel déjà construite à l’étape précédente.

### Principes à conserver
- structure **MVC**
- vues **Blade**
- utilisation de **Eloquent**
- base de données **MySQL** déjà utilisée dans l’étape 4 / 6
- logique simple et compréhensible pour un rendu étudiant

---

## 2. Étape 7 — API REST

### Objectif
Créer au moins **une route API fonctionnelle** et l’exploiter côté interface avec `fetch()`.

### Fonctionnalités attendues
- **GET /api/tickets** : récupérer les tickets en JSON
- **POST /api/tickets** : ajouter un ticket sans rechargement de page
- affichage dynamique des tickets depuis l’API
- retour JSON propre avec gestion des erreurs

### Recommandation simple
Pour rester cohérent avec le projet existant :
- utiliser `routes/api.php` pour les routes API
- créer un contrôleur API dédié aux tickets
- renvoyer des réponses JSON claires et lisibles
- utiliser `fetch()` dans une vue Blade ou un fichier JavaScript séparé

### Exemple de flux
1. la page charge la liste des tickets via `fetch()`
2. l’utilisateur ajoute un ticket via un formulaire
3. la requête part en `POST` vers l’API
4. la liste se met à jour sans rechargement

### Authentification API
Si nécessaire, l’authentification peut être gérée avec **Sanctum**.

Pour un rendu simple :
- garder l’auth web classique si l’API reste interne à l’application
- ajouter Sanctum seulement si une vraie séparation front / API est voulue

---

## 3. Étape 8 — Finalisation / amélioration

### Objectif
Rendre l’application plus complète, plus propre et plus présentable.

### Améliorations possibles
- **validation des tickets facturables** par le client
- **gestion de rôles** utilisateurs
- **sécurité basique**
- petites améliorations UX
- **README explicatif**

### Version simple recommandée
Pour rester dans quelque chose de réaliste et livrable, il est conseillé de conserver :
- un rôle **admin**
- un rôle **collaborateur**
- un rôle **client**

### Sécurité minimale
- validation des formulaires côté serveur
- contrôle d’accès sur les routes sensibles
- protection CSRF sur les formulaires web
- pas de mot de passe en clair
- messages d’erreur propres

### UX minimale
- navigation lisible
- boutons alignés
- formulaires cohérents
- retour visuel après ajout / suppression / modification
- affichage clair des tickets et de leur état

---

## 4. Organisation conseillée des fichiers

### Routes
- `routes/web.php`
- `routes/api.php`

### Contrôleurs
- contrôleurs web pour les pages classiques
- contrôleur API pour les tickets

### Vues
- vues Blade pour l’interface principale
- un fichier JS dédié pour les appels `fetch()`

### Ressources
- `resources/views/...`
- `resources/js/...`
- `resources/css/...` si besoin

### Implémentation retenue dans ce projet
- `routes/api.php` : routes API des tickets
- `app/Http/Controllers/TicketApiController.php` : retour JSON et création de ticket
- `public/api-tickets.js` : consommation de l’API avec `fetch()`
- `resources/views/tickets/index.blade.php` : formulaire et affichage dynamique depuis l’API

---

## 5. Ce que doit montrer le rendu final

Le projet final doit prouver que :
- l’application est structurée correctement
- les tickets peuvent être lus et créés via l’API
- le front consomme bien l’API avec `fetch()`
- la navigation reste simple et propre
- la base de données est utilisée correctement
- les améliorations bonus sont présentes si possible

---

## 6. Checklist de validation

- [ ] l’application Laravel démarre correctement
- [ ] la base MySQL est bien connectée
- [ ] l’API tickets répond en JSON
- [ ] l’ajout d’un ticket fonctionne via `fetch()`
- [ ] les tickets s’affichent dynamiquement
- [ ] les routes sensibles sont protégées
- [ ] les rôles sont gérés proprement
- [ ] l’interface reste lisible et cohérente
- [ ] le README explique le fonctionnement du projet

---

## 7. Conclusion

Cette étape 7 & 8 sert de **finalisation du fil rouge**.

Le but n’est pas de complexifier inutilement le projet, mais de montrer une application Laravel :
- propre,
- organisée,
- connectée à une base MySQL,
- enrichie par une petite API,
- et prête à être rendue.

