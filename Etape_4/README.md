# Application de Gestion de Ticketing - Etape 4 (MySQL)

## Objectifs pedagogiques

- Persister les donnees en base MySQL
- Concevoir un modele relationnel simple
- Utiliser des requetes SQL propres et preparees
- Gerer tickets inclus et facturables

## Prerequis

- PHP avec `pdo` et `pdo_mysql`
- Serveur MySQL/MariaDB actif

## Configuration MySQL

1) Copier la configuration d'environnement:

```bash
cp .env.example .env
```

2) Mettre les variables MySQL dans `.env`:

- `DB_DRIVER=mysql`
- `DB_HOST=127.0.0.1`
- `DB_PORT=3306`
- `DB_NAME=fil_rouge_etape4`
- `DB_USER=fil_rouge`
- `DB_PASSWORD=VotreMotDePasse`
- `DB_CHARSET=utf8mb4`

3) Creer la base + utilisateur (recommande):

```sql
CREATE DATABASE IF NOT EXISTS fil_rouge_etape4 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'fil_rouge'@'localhost' IDENTIFIED BY 'VotreMotDePasse';
GRANT ALL PRIVILEGES ON fil_rouge_etape4.* TO 'fil_rouge'@'localhost';
FLUSH PRIVILEGES;
```

> Si vous avez `Access denied for user 'root'@'localhost'`, utilisez un utilisateur dedie (`fil_rouge`) au lieu de `root`.

## Initialisation / reset base

```bash
php database/reset_db.php
```

## Demarrage application

```bash
cd public
php -S localhost:8003
```

Puis ouvrir `http://localhost:8003`.

## Parametres DataGrip

- Type: `MySQL`
- Host: `127.0.0.1`
- Port: `3306`
- Database: `fil_rouge_etape4`
- User: `fil_rouge`
- Password: `VotreMotDePasse`

## Comptes de test

**ATTENTION : Changer ces mots de passe pour un usage réel !**

- `admin@et.esiea.fr` / `[voir fichier local 002_seed.sql]`
- `collab@et.esiea.fr` / `[voir fichier local 002_seed.sql]`
- `client@et.esiea.fr` / `[voir fichier local 002_seed.sql]`

## Fonctionnalites livrees

- Authentification SQL (`login`, `register`, `logout`, `forgot password`)
- CRUD principal sur tickets/projets (creation + lecture + mise a jour ticket)
- Suppression projet (avec suppression des tickets associes)
- Suppression ticket
- Profil et parametres utilisateur persistes en base
- Distinction ticket `Inclus` / `Facturable`

## Structure finale (sans doublons)

### Pages publiques (une seule version)

- `public/index.php`
- `public/dashboard.php`
- `public/projects.php`
- `public/project-detail.php`
- `public/project-create.php`
- `public/tickets.php`
- `public/ticket-detail.php`
- `public/ticket-create.php`
- `public/ticket-edit.php`
- `public/profile.php`
- `public/settings.php`
- `public/register.php`
- `public/forgot-password.php`

### Actions backend (organisees par domaine)

- `public/actions/auth/login.php`
- `public/actions/auth/logout.php`
- `public/actions/auth/register.php`
- `public/actions/auth/forgot_password.php`
- `public/actions/projects/create_project.php`
- `public/actions/projects/delete_project.php`
- `public/actions/tickets/create_ticket.php`
- `public/actions/tickets/update_ticket.php`
- `public/actions/tickets/delete_ticket.php`
- `public/actions/user/update_profile.php`
- `public/actions/user/update_settings.php`

## Modele de donnees

Tables:

- `users`
- `clients`
- `contrats`
- `projects`
- `tickets`
- `temps_passes`

Scripts SQL:

- `database/001_schema.sql`
- `database/002_seed.sql`

## Securite et qualite

- Requetes preparees PDO
- Controle d'acces via `requireAuth()`
- Suppressions en `POST` uniquement
- Transactions SQL pour les suppressions sensibles
- Messages flash succes/erreur

## Checklist livrable

- [ ] Connexion MySQL OK
- [ ] `php database/reset_db.php` execute sans erreur
- [ ] Login/Logout fonctionnels
- [ ] Creation ticket/projet fonctionnelle
- [ ] Lecture tickets/projets depuis la BDD
- [ ] Suppression ticket fonctionnelle
- [ ] Suppression projet + cascade tickets fonctionnelle
- [ ] DataGrip connecte sur `fil_rouge_etape4`
