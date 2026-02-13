// GESTION DES MESSAGES D'ERREUR ET DE SUCCÈS

/**
 * Affiche un message d'erreur ou de succès
 * @param {string} message - Le message à afficher
 * @param {string} type - Type de message ('error' ou 'success')
 */
function afficherMessage(message, type) {
    // Supprimer l'ancien message s'il existe
    const ancienMessage = document.querySelector('.message-alerte');
    if (ancienMessage) {
        ancienMessage.remove();
    }

    // Créer le nouveau message
    const divMessage = document.createElement('div');
    divMessage.className = `message-alerte message-${type}`;
    divMessage.textContent = message;

    // Ajouter le message avant le premier élément du main
    const main = document.querySelector('main');
    if (main && main.firstChild) {
        main.insertBefore(divMessage, main.firstChild);
    }

    // Supprimer le message après 5 secondes
    setTimeout(() => {
        divMessage.remove();
    }, 5000);
}

/**
 * Affiche un message d'erreur sous un champ de formulaire
 * @param {HTMLElement} champ - Le champ de formulaire
 * @param {string} message - Le message d'erreur
 */
function afficherErreurChamp(champ, message) {
    // Supprimer l'erreur existante
    const ancienneErreur = champ.parentElement.querySelector('.erreur-champ');
    if (ancienneErreur) {
        ancienneErreur.remove();
    }

    // Créer et ajouter la nouvelle erreur
    const spanErreur = document.createElement('span');
    spanErreur.className = 'erreur-champ';
    spanErreur.textContent = message;
    champ.parentElement.appendChild(spanErreur);
    
    // Ajouter une classe d'erreur au champ
    champ.classList.add('champ-erreur');
}

/**
 * Supprime le message d'erreur d'un champ
 * @param {HTMLElement} champ - Le champ de formulaire
 */
function supprimerErreurChamp(champ) {
    const erreur = champ.parentElement.querySelector('.erreur-champ');
    if (erreur) {
        erreur.remove();
    }
    champ.classList.remove('champ-erreur');
}

// VALIDATION DU FORMULAIRE DE CONNEXION

/**
 * Valide le format d'email étudiant ESIEA
 * @param {string} email - L'adresse email à valider
 * @returns {boolean}
 */
function validerEmailEtudiant(email) {
    // Vérifier que l'email n'est pas vide
    if (!email || email.trim() === '') {
        return false;
    }
    
    // Vérifier le format @et.esiea.fr
    const regex = /^[a-zA-Z0-9._-]+@et\.esiea\.fr$/;
    return regex.test(email);
}

/**
 * Valide le mot de passe (minimum 6 caractères)
 * @param {string} password - Le mot de passe à valider
 * @returns {boolean}
 */
function validerMotDePasse(password) {
    // Vérifier que le mot de passe n'est pas vide
    if (!password || password.trim() === '') {
        return false;
    }
    
    // Minimum 6 caractères
    return password.length >= 6;
}

/**
 * Initialise la validation du formulaire de connexion
 */
function initConnexion() {
    const form = document.querySelector('form');
    if (!form) return;

    const emailInput = document.getElementById('email');
    const passInput = document.getElementById('pass');

    // Validation en temps réel lors de la saisie
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            supprimerErreurChamp(this);
        });

        emailInput.addEventListener('blur', function() {
            const email = this.value.trim();
            
            if (email === '') {
                afficherErreurChamp(this, 'L\'email est obligatoire');
            } else if (!validerEmailEtudiant(email)) {
                afficherErreurChamp(this, 'Utilisez votre email étudiant @et.esiea.fr');
            }
        });
    }

    if (passInput) {
        passInput.addEventListener('input', function() {
            supprimerErreurChamp(this);
        });

        passInput.addEventListener('blur', function() {
            const password = this.value;
            
            if (password === '') {
                afficherErreurChamp(this, 'Le mot de passe est obligatoire');
            } else if (!validerMotDePasse(password)) {
                afficherErreurChamp(this, 'Le mot de passe doit contenir au moins 6 caractères');
            }
        });
    }

    // Validation à la soumission du formulaire
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let estValide = true;

        // Valider l'email
        if (emailInput) {
            const email = emailInput.value.trim();
            
            if (email === '') {
                afficherErreurChamp(emailInput, 'L\'email est obligatoire');
                estValide = false;
            } else if (!validerEmailEtudiant(email)) {
                afficherErreurChamp(emailInput, 'Utilisez votre email étudiant @et.esiea.fr');
                estValide = false;
            }
        }

        // Valider le mot de passe
        if (passInput) {
            const password = passInput.value;
            
            if (password === '') {
                afficherErreurChamp(passInput, 'Le mot de passe est obligatoire');
                estValide = false;
            } else if (!validerMotDePasse(password)) {
                afficherErreurChamp(passInput, 'Le mot de passe doit contenir au moins 6 caractères');
                estValide = false;
            }
        }

        // Si tout est valide, afficher un message de succès et rediriger
        if (estValide) {
            afficherMessage('Connexion réussie ! Redirection...', 'success');
            setTimeout(() => {
                window.location.href = form.action || 'dashboard.html';
            }, 1500);
        } else {
            afficherMessage('Veuillez corriger les erreurs du formulaire', 'error');
        }
    });
}

// VALIDATION DU FORMULAIRE D'INSCRIPTION

/**
 * Valide le nom complet (minimum 2 caractères)
 * @param {string} nom - Le nom à valider
 * @returns {boolean}
 */
function validerNom(nom) {
    if (!nom || nom.trim() === '') {
        return false;
    }
    return nom.trim().length >= 2;
}

/**
 * Valide l'email professionnel
 * @param {string} email - L'email à valider
 * @returns {boolean}
 */
function validerEmail(email) {
    if (!email || email.trim() === '') {
        return false;
    }
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * Initialise la validation du formulaire d'inscription
 */
function initInscription() {
    const form = document.querySelector('form');
    if (!form) return;

    const nomInput = document.getElementById('nom');
    const emailInput = document.getElementById('email');
    const passInput = document.getElementById('pass');

    // Validation en temps réel
    if (nomInput) {
        nomInput.addEventListener('input', function() {
            supprimerErreurChamp(this);
        });

        nomInput.addEventListener('blur', function() {
            const nom = this.value.trim();
            
            if (nom === '') {
                afficherErreurChamp(this, 'Le nom est obligatoire');
            } else if (!validerNom(nom)) {
                afficherErreurChamp(this, 'Le nom doit contenir au moins 2 caractères');
            }
        });
    }

    if (emailInput) {
        emailInput.addEventListener('input', function() {
            supprimerErreurChamp(this);
        });

        emailInput.addEventListener('blur', function() {
            const email = this.value.trim();
            
            if (email === '') {
                afficherErreurChamp(this, 'L\'email est obligatoire');
            } else if (!validerEmail(email)) {
                afficherErreurChamp(this, 'Format d\'email invalide');
            }
        });
    }

    if (passInput) {
        passInput.addEventListener('input', function() {
            supprimerErreurChamp(this);
        });

        passInput.addEventListener('blur', function() {
            const password = this.value;
            
            if (password === '') {
                afficherErreurChamp(this, 'Le mot de passe est obligatoire');
            } else if (!validerMotDePasse(password)) {
                afficherErreurChamp(this, 'Le mot de passe doit contenir au moins 6 caractères');
            }
        });
    }

    // Validation à la soumission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let estValide = true;

        // Valider le nom
        if (nomInput) {
            const nom = nomInput.value.trim();
            
            if (nom === '') {
                afficherErreurChamp(nomInput, 'Le nom est obligatoire');
                estValide = false;
            } else if (!validerNom(nom)) {
                afficherErreurChamp(nomInput, 'Le nom doit contenir au moins 2 caractères');
                estValide = false;
            }
        }

        // Valider l'email
        if (emailInput) {
            const email = emailInput.value.trim();
            
            if (email === '') {
                afficherErreurChamp(emailInput, 'L\'email est obligatoire');
                estValide = false;
            } else if (!validerEmail(email)) {
                afficherErreurChamp(emailInput, 'Format d\'email invalide');
                estValide = false;
            }
        }

        // Valider le mot de passe
        if (passInput) {
            const password = passInput.value;
            
            if (password === '') {
                afficherErreurChamp(passInput, 'Le mot de passe est obligatoire');
                estValide = false;
            } else if (!validerMotDePasse(password)) {
                afficherErreurChamp(passInput, 'Le mot de passe doit contenir au moins 6 caractères');
                estValide = false;
            }
        }

        if (estValide) {
            afficherMessage('Inscription réussie ! Redirection vers la connexion...', 'success');
            setTimeout(() => {
                window.location.href = form.action || 'index.html';
            }, 1500);
        } else {
            afficherMessage('Veuillez corriger les erreurs du formulaire', 'error');
        }
    });
}

// VALIDATION DU FORMULAIRE DE CRÉATION DE TICKET

/**
 * Valide le titre du ticket (minimum 5 caractères)
 * @param {string} titre - Le titre à valider
 * @returns {boolean}
 */
function validerTitre(titre) {
    if (!titre || titre.trim() === '') {
        return false;
    }
    return titre.trim().length >= 5;
}

/**
 * Valide la description du ticket (minimum 10 caractères)
 * @param {string} description - La description à valider
 * @returns {boolean}
 */
function validerDescription(description) {
    if (!description || description.trim() === '') {
        return false;
    }
    return description.trim().length >= 10;
}

/**
 * Initialise la validation du formulaire de création de ticket
 */
function initCreationTicket() {
    const form = document.querySelector('form');
    if (!form) return;

    const titreInput = document.getElementById('titre');
    const descInput = document.getElementById('desc');
    const projetSelect = document.getElementById('projet-select');

    // Validation en temps réel
    if (titreInput) {
        titreInput.addEventListener('input', function() {
            supprimerErreurChamp(this);
        });

        titreInput.addEventListener('blur', function() {
            const titre = this.value.trim();
            
            if (titre === '') {
                afficherErreurChamp(this, 'Le sujet du ticket est obligatoire');
            } else if (!validerTitre(titre)) {
                afficherErreurChamp(this, 'Le sujet doit contenir au moins 5 caractères');
            }
        });
    }

    if (descInput) {
        descInput.addEventListener('input', function() {
            supprimerErreurChamp(this);
        });

        descInput.addEventListener('blur', function() {
            const description = this.value.trim();
            
            if (description === '') {
                afficherErreurChamp(this, 'La description est obligatoire');
            } else if (!validerDescription(description)) {
                afficherErreurChamp(this, 'La description doit contenir au moins 10 caractères');
            }
        });
    }

    if (projetSelect) {
        projetSelect.addEventListener('change', function() {
            supprimerErreurChamp(this);
        });
    }

    // Validation à la soumission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let estValide = true;

        // Valider le titre
        if (titreInput) {
            const titre = titreInput.value.trim();
            
            if (titre === '') {
                afficherErreurChamp(titreInput, 'Le sujet du ticket est obligatoire');
                estValide = false;
            } else if (!validerTitre(titre)) {
                afficherErreurChamp(titreInput, 'Le sujet doit contenir au moins 5 caractères');
                estValide = false;
            }
        }

        // Valider le projet
        if (projetSelect) {
            if (projetSelect.value === '') {
                afficherErreurChamp(projetSelect, 'Vous devez sélectionner un projet');
                estValide = false;
            }
        }

        // Valider la description
        if (descInput) {
            const description = descInput.value.trim();
            
            if (description === '') {
                afficherErreurChamp(descInput, 'La description est obligatoire');
                estValide = false;
            } else if (!validerDescription(description)) {
                afficherErreurChamp(descInput, 'La description doit contenir au moins 10 caractères');
                estValide = false;
            }
        }

        if (estValide) {
            afficherMessage('Ticket créé avec succès ! Redirection...', 'success');
            setTimeout(() => {
                window.location.href = form.action || 'tickets.html';
            }, 1500);
        } else {
            afficherMessage('Veuillez corriger les erreurs du formulaire', 'error');
        }
    });
}

// SYSTÈME DE FILTRAGE DES TICKETS

/**
 * Initialise le système de filtrage des tickets
 */
function initFiltresTickets() {
    const recherche = document.getElementById('recherche');
    const tableau = document.querySelector('.tableau-liste tbody');
    
    if (!tableau) return;

    // Créer les boutons de filtrage
    creerBoutonsFiltres();

    // Fonction de filtrage
    function filtrerTickets() {
        const termesRecherche = recherche ? recherche.value.toLowerCase() : '';
        const filtreType = document.querySelector('.filtre-type.actif');
        const filtreStatut = document.querySelector('.filtre-statut.actif');
        const filtrePriorite = document.querySelector('.filtre-priorite.actif');

        const lignes = tableau.querySelectorAll('tr');
        
        lignes.forEach(ligne => {
            const id = ligne.cells[0].textContent.toLowerCase();
            const sujet = ligne.cells[1].textContent.toLowerCase();
            const type = ligne.cells[2].textContent.trim();
            const statut = ligne.cells[5].textContent.trim();
            const priorite = ligne.cells[4].textContent.trim();

            // Vérifier la recherche
            const correspondRecherche = !termesRecherche || 
                id.includes(termesRecherche) || 
                sujet.includes(termesRecherche);

            // Vérifier le filtre de type
            let correspondType = true;
            if (filtreType) {
                const typeFiltre = filtreType.dataset.type;
                if (typeFiltre === 'inclus') {
                    correspondType = type === 'Inclus';
                } else if (typeFiltre === 'facturable') {
                    correspondType = type === 'Facturable';
                }
            }

            // Vérifier le filtre de statut
            let correspondStatut = true;
            if (filtreStatut) {
                const statutFiltre = filtreStatut.dataset.statut;
                correspondStatut = statut === statutFiltre;
            }

            // Vérifier le filtre de priorité
            let correspondPriorite = true;
            if (filtrePriorite) {
                const prioriteFiltre = filtrePriorite.dataset.priorite;
                correspondPriorite = priorite === prioriteFiltre;
            }

            // Afficher ou masquer la ligne
            if (correspondRecherche && correspondType && correspondStatut && correspondPriorite) {
                ligne.style.display = '';
            } else {
                ligne.style.display = 'none';
            }
        });

        // Afficher un message si aucun résultat
        afficherMessageAucunResultat(lignes);
    }

    // Écouter la saisie dans la recherche
    if (recherche) {
        recherche.addEventListener('input', filtrerTickets);
    }

    // Écouter le clic sur le bouton Filtrer
    const boutonFiltrer = document.querySelector('.bouton-rechercher');
    if (boutonFiltrer) {
        boutonFiltrer.addEventListener('click', filtrerTickets);
    }

    // Écouter les clics sur les boutons de filtre
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('filtre-type') || 
            e.target.classList.contains('filtre-statut') ||
            e.target.classList.contains('filtre-priorite')) {
            
            // Retirer la classe actif des autres filtres du même groupe
            const groupe = e.target.classList.contains('filtre-type') ? 'filtre-type' :
                          e.target.classList.contains('filtre-statut') ? 'filtre-statut' :
                          'filtre-priorite';
            
            document.querySelectorAll(`.${groupe}`).forEach(btn => {
                btn.classList.remove('actif');
            });

            // Activer/désactiver le filtre cliqué
            if (e.target.classList.contains('actif')) {
                e.target.classList.remove('actif');
            } else {
                e.target.classList.add('actif');
            }

            filtrerTickets();
        }
    });
}

/**
 * Crée les boutons de filtrage pour les tickets
 */
function creerBoutonsFiltres() {
    const barreFiltres = document.querySelector('.barre-filtres');
    if (!barreFiltres) return;

    // Vérifier si les boutons existent déjà
    if (document.querySelector('.filtres-type')) return;

    // Créer le conteneur des filtres de type
    const divFiltresType = document.createElement('div');
    divFiltresType.className = 'filtres-groupe filtres-type';
    divFiltresType.innerHTML = `
        <label>Type :</label>
        <button type="button" class="filtre-type" data-type="tous">Tous</button>
        <button type="button" class="filtre-type" data-type="inclus">Inclus</button>
        <button type="button" class="filtre-type" data-type="facturable">Facturable</button>
    `;

    // Créer le conteneur des filtres de statut
    const divFiltresStatut = document.createElement('div');
    divFiltresStatut.className = 'filtres-groupe filtres-statut';
    divFiltresStatut.innerHTML = `
        <label>Statut :</label>
        <button type="button" class="filtre-statut" data-statut="Nouveau">Nouveau</button>
        <button type="button" class="filtre-statut" data-statut="En cours">En cours</button>
        <button type="button" class="filtre-statut" data-statut="À valider">À valider</button>
    `;

    // Créer le conteneur des filtres de priorité
    const divFiltresPriorite = document.createElement('div');
    divFiltresPriorite.className = 'filtres-groupe filtres-priorite';
    divFiltresPriorite.innerHTML = `
        <label>Priorité :</label>
        <button type="button" class="filtre-priorite" data-priorite="Basse">Basse</button>
        <button type="button" class="filtre-priorite" data-priorite="Moyenne">Moyenne</button>
        <button type="button" class="filtre-priorite" data-priorite="Critique">Critique</button>
    `;

    // Insérer les filtres avant le bouton Filtrer
    const boutonFiltrer = barreFiltres.querySelector('.bouton-rechercher');
    if (boutonFiltrer) {
        barreFiltres.insertBefore(divFiltresType, boutonFiltrer);
        barreFiltres.insertBefore(divFiltresStatut, boutonFiltrer);
        barreFiltres.insertBefore(divFiltresPriorite, boutonFiltrer);
    }
}

/**
 * Affiche un message si aucun ticket ne correspond aux filtres
 * @param {NodeList} lignes - Les lignes du tableau
 */
function afficherMessageAucunResultat(lignes) {
    const tableau = document.querySelector('.tableau-liste');
    if (!tableau) return;

    // Compter les lignes visibles
    let nombreVisibles = 0;
    lignes.forEach(ligne => {
        if (ligne.style.display !== 'none') {
            nombreVisibles++;
        }
    });

    // Supprimer l'ancien message
    const ancienMessage = document.querySelector('.message-aucun-resultat');
    if (ancienMessage) {
        ancienMessage.remove();
    }

    // Afficher le message si aucun résultat
    if (nombreVisibles === 0) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message-aucun-resultat';
        messageDiv.textContent = 'Aucun ticket ne correspond à vos critères de recherche';
        tableau.parentElement.appendChild(messageDiv);
    }
}

// INITIALISATION AU CHARGEMENT DE LA PAGE

document.addEventListener('DOMContentLoaded', function() {
    // Détecter quelle page est chargée et initialiser les fonctions appropriées
    const path = window.location.pathname;
    const page = path.substring(path.lastIndexOf('/') + 1);

    console.log('Page chargée:', page);

    // Initialiser selon la page
    if (page === 'index.html' || page === '') {
        console.log('Initialisation de la connexion');
        initConnexion();
    } 
    else if (page === 'register.html') {
        console.log('Initialisation de l\'inscription');
        initInscription();
    } 
    else if (page === 'ticket-create.html') {
        console.log('Initialisation de la création de ticket');
        initCreationTicket();
    } 
    else if (page === 'tickets.html') {
        console.log('Initialisation des filtres de tickets');
        initFiltresTickets();
    }
});
