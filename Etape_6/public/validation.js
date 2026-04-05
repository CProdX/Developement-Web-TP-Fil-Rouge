/**
 * Application de Gestion de Ticketing - Scripts Centralisés
 * ESIEA - Étape 3 (PHP Procédural)
 *
 * Fichier JS unique contenant :
 * - Navigation et affichage des boutons contextuels
 * - Validation de formulaires (tous les formulaires)
 * - Gestion des messages flash
 * - Validation des champs requis
 */

document.addEventListener('DOMContentLoaded', function() {
    'use strict';


    // 1. NAVIGATION ET AFFICHAGE CONTEXTUEL

    (function() {
        const body = document.body;
        if (!body) return;

        const activePage = body.getAttribute('data-active-page') || '';
        const newTicketLink = document.getElementById('nav-new-ticket');
        const newProjectLink = document.getElementById('nav-new-project');

        if (newTicketLink && newProjectLink) {
            const showTicketLink = activePage === 'tickets';
            const showProjectLink = activePage === 'projects';

            newTicketLink.style.visibility = showTicketLink ? 'visible' : 'hidden';
            newTicketLink.style.pointerEvents = showTicketLink ? 'auto' : 'none';

            newProjectLink.style.visibility = showProjectLink ? 'visible' : 'hidden';
            newProjectLink.style.pointerEvents = showProjectLink ? 'auto' : 'none';
        }
    })();


    // 2. GESTION DES MESSAGES FLASH

    (function() {
        const flashMessages = document.querySelectorAll('.message-success, .message-error');
        flashMessages.forEach(function(message) {
            setTimeout(function() {
                message.remove();
            }, 1000);
        });
    })();

    // 3. VALIDATION DE TOUS LES FORMULAIRES


    // Configuration des règles de validation par formulaire
    const validationRules = {
        // Authentification
        'form[action*="login"]': {
            email: { required: true, email: true, studentEmail: true },
            password: { required: true, minLength: 8 }
        },
        'form[action*="register"]': {
            name: { required: true, minLength: 2 },
            email: { required: true, email: true, studentEmail: true },
            password: { required: true, minLength: 8 }
        },
        'form[action*="forgot_password"]': {
            email: { required: true, email: true, studentEmail: true }
        },

        // Gestion utilisateur
        'form[action*="update_profile"]': {
            name: { required: true, minLength: 2 },
            email: { required: true, email: true, studentEmail: true }
        },
        'form[action*="update_settings"]': {
            lang: { required: true },
            notif: { required: true }
        },

        // Projets
        'form[action*="create_project"]': {
            nom: { required: true, minLength: 3 },
            client: { required: true, minLength: 2 },
            description: { required: true, minLength: 10 }
        },

        // Tickets
        'form[action*="create_ticket"]': {
            titre: { required: true, minLength: 5 },
            type: { required: true },
            project_id: { required: true },
            priorite: { required: true },
            description: { required: true, minLength: 10 }
        },
        'form[action*="update_ticket"]': {
            statut: { required: true },
            priorite: { required: true },
            temps: { required: true, timeFormat: true },
            description: { required: true, minLength: 10 }
        }
    };

    // Expressions régulières pour la validation
    const REGEXES = {
        email: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/i,
        studentEmail: /^[a-zA-Z0-9._%+-]+@et\.esiea\.fr$/i,
        timeFormat: /^([0-1]?[0-9]|2[0-3]):([0-5][0-9])$/
    };

    // Déterminer le formulaire actif
    let currentForm = null;
    let currentRules = null;

    for (const selector in validationRules) {
        const form = document.querySelector(selector);
        if (form) {
            currentForm = form;
            currentRules = validationRules[selector];
            break;
        }
    }

    if (currentForm && currentRules) {
        // Fonctions d'affichage d'erreurs
        function setError(input, message) {
            let error = input.parentElement.querySelector('.message-erreur');
            if (!error) {
                error = document.createElement('small');
                error.className = 'message-erreur';
                error.style.color = '#c62828';
                error.style.display = 'block';
                error.style.marginTop = '6px';
                input.parentElement.appendChild(error);
            }
            error.textContent = message;
            input.setAttribute('aria-invalid', 'true');
        }

        function clearError(input) {
            const error = input.parentElement.querySelector('.message-erreur');
            if (error) error.remove();
            input.removeAttribute('aria-invalid');
        }

        function getLabelText(input) {
            const label = input.parentElement.querySelector('label');
            return label ? label.textContent.trim() : 'Ce champ';
        }

        // Fonction de validation d'un champ
        function validateField(fieldName, input) {
            const rules = currentRules[fieldName];
            if (!rules) return true;

            const value = input.value.trim();

            // Vérification obligatoire
            if (rules.required && !value) {
                if (fieldName === 'password') {
                    setError(input, 'Le mot de passe est obligatoire.');
                } else {
                    setError(input, `${getLabelText(input)} est obligatoire.`);
                }
                return false;
            }

            if (!value) {
                clearError(input);
                return true;
            }

            // Longueur minimale
            if (rules.minLength && value.length < rules.minLength) {
                if (fieldName === 'password') {
                    setError(input, `Le mot de passe doit contenir au moins ${rules.minLength} caractères.`);
                } else {
                    setError(input, `Minimum ${rules.minLength} caractères requis.`);
                }
                return false;
            }

            // Email standard
            if (rules.email && !REGEXES.email.test(value)) {
                setError(input, 'Adresse email invalide.');
                return false;
            }

            // Email étudiant ESIEA
            if (rules.studentEmail && !REGEXES.studentEmail.test(value)) {
                setError(input, 'Utilisez un email étudiant valide (ex: nom@et.esiea.fr).');
                return false;
            }

            // Format temps (HH:MM)
            if (rules.timeFormat && !REGEXES.timeFormat.test(value)) {
                setError(input, 'Format temps invalide (ex: 02:30).');
                return false;
            }

            clearError(input);
            return true;
        }

        // Ajouter les écouteurs de validation
        for (const fieldName in currentRules) {
            const input = currentForm.querySelector(`[name="${fieldName}"]`);
            if (!input) continue;

            // Validation au blur
            input.addEventListener('blur', function() {
                validateField(fieldName, this);
            });

            // Nettoyage de l'erreur à la saisie
            input.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    clearError(this);
                }
            });

            input.addEventListener('change', function() {
                clearError(this);
            });
        }

        // Validation à la soumission
        currentForm.addEventListener('submit', function(e) {
            let isValid = true;

            for (const fieldName in currentRules) {
                const input = currentForm.querySelector(`[name="${fieldName}"]`);
                if (input && !validateField(fieldName, input)) {
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }


    // 4. VALIDATION BASIQUE DES CHAMPS REQUIS (fallback)

    (function() {
        function clearFieldError(field) {
            field.classList.remove('champ-erreur');
            const error = field.parentElement ? field.parentElement.querySelector('.erreur-champ') : null;
            if (error) error.remove();
        }

        function showFieldError(field, message) {
            clearFieldError(field);
            field.classList.add('champ-erreur');

            if (!field.parentElement) return;
            const error = document.createElement('small');
            error.className = 'erreur-champ';
            error.textContent = message;
            field.parentElement.appendChild(error);
        }

        function validateRequiredForm(form) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');

            requiredFields.forEach(function(field) {
                clearFieldError(field);

                const value = (field.value || '').trim();
                if (value === '') {
                    const label = form.querySelector('label[for="' + field.id + '"]');
                    const name = label ? label.textContent.trim() : 'Ce champ';
                    showFieldError(field, name + ' est obligatoire.');
                    isValid = false;
                }
            });

            return isValid;
        }

        // Ajouter les écouteurs pour tous les formulaires
        const forms = document.querySelectorAll('form');
        forms.forEach(function(form) {
            const method = (form.getAttribute('method') || 'get').toLowerCase();
            if (method !== 'post') return;

            form.addEventListener('submit', function(event) {
                if (!validateRequiredForm(form)) {
                    event.preventDefault();
                }
            });

            form.querySelectorAll('[required]').forEach(function(field) {
                field.addEventListener('input', function() {
                    if ((field.value || '').trim() !== '') {
                        clearFieldError(field);
                    }
                });
            });
        });
    })();
});
