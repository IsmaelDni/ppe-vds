"use strict";

// -----------------------------------------------------------------------------------
// Import des fonctions nécessaires
// -----------------------------------------------------------------------------------

import {initialiserToutesLesCartes, basculerToutesLesCartes} from "/composant/fonction/openclose.js?";
import {formatDateLong} from "/composant/fonction/date.js";

// -----------------------------------------------------------------------------------
// Déclaration des variables globales
// -----------------------------------------------------------------------------------

/* global prochaineEdition, lesClassements, lesPartenaires*/

// Récupération des éléments de l'interface
const detailClassement = document.getElementById('detailClassement');
const dateEpreuve = document.getElementById('dateEpreuve');
const descriptionEpreuve = document.getElementById('descriptionEpreuve');
const btnOuvrirToutes = document.getElementById('btnOuvrirToutes');
const btnFermerToutes = document.getElementById('btnFermerToutes');
const container = document.getElementById('detailPartenaire');

// -----------------------------------------------------------------------------------
// Procédures évènementielles
// -----------------------------------------------------------------------------------

btnOuvrirToutes.onclick = () => basculerToutesLesCartes(true);
btnFermerToutes.onclick = () => basculerToutesLesCartes(false); // fermer


// -----------------------------------------------------------------------------------
// Programme principal
// -----------------------------------------------------------------------------------

// Mise en place du système d'ouverture/fermeture des cadres
initialiserToutesLesCartes();

// les informations

// affichage de la prochaine épreuve
dateEpreuve.innerText =  formatDateLong(prochaineEdition.date);
descriptionEpreuve.innerHTML = prochaineEdition.description;


// afficher les derniers classements pdf
for (const element of lesClassements) {
    let a = document.createElement('a');
    a.classList.add('lien'),
        a.href = "/afficherclassement.php?id=" + element.id;
    a.innerText = element.dateFr + ' ' + element.titre;
    detailClassement.appendChild(a);
}


    // -----------------------------------------------------------------------------
    // Affichage des partenaires fournis par le serveur (injection via index.php)
    // -----------------------------------------------------------------------------
    function afficherPartenairesServeur() {
    if (!container) return;
    try {
        container.innerHTML = '';
        if (typeof lesPartenaires === 'undefined' || !Array.isArray(lesPartenaires) || lesPartenaires.length === 0) return;
        for (const p of lesPartenaires) {
            const a = document.createElement('a');
            a.href = p.url || '#';
            a.target = '_blank';
            a.style.display = 'inline-block';
            a.style.margin = '6px';
            const img = document.createElement('img');
            img.src = p.fichier ? '/data/partenaire/' + p.fichier : '/data/partenaire/.keep';
            img.alt = p.nom || '';
            img.style.maxHeight = '100px';
            img.style.height = 'auto';
            img.style.display = 'block';
            a.appendChild(img);
            container.appendChild(a);
        }
    } catch (e) {
        console.error('Erreur affichage partenaires', e);
    }
}

document.addEventListener('DOMContentLoaded', afficherPartenairesServeur);










