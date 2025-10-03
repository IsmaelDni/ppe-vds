"use strict";

import {appelAjax} from "/composant/fonction/ajax.js";
import {confirmer, messageBox, corriger} from "/composant/fonction/afficher.js";
import {creerBoutonSuppression, creerBoutonModification} from "/composant/fonction/formulaire.js";

/* global lesPartenaires */

const lesLignes = document.getElementById('lesLignes');
const nb = document.getElementById('nb');

nb.innerText = lesPartenaires.length;

for (const element of lesPartenaires) {
    const id = element.id;
    const tr = document.createElement('tr');
    tr.id = 'p-' + id;

    // logo
    const tdLogo = document.createElement('td');
    tdLogo.style.textAlign = 'center';
    if (element.fichier) {
        const img = document.createElement('img');
        img.src = '/data/partenaire/' + element.fichier;
        img.style.height = '50px';
        tdLogo.appendChild(img);
    }
    tr.appendChild(tdLogo);

    // nom (editable)
    const tdNom = document.createElement('td');
    const inputNom = document.createElement('input');
    inputNom.type = 'text';
    inputNom.value = element.nom;
    inputNom.dataset.old = element.nom;
    inputNom.onchange = function () {
        if (this.value !== this.dataset.old) {
            appelAjax({
                url: '/ajax/modifier.php',
                data: {
                    table: 'partenaire',
                    id: id,
                    lesValeurs: JSON.stringify({nom: this.value})
                },
                success: () => {
                    this.dataset.old = this.value;
                    this.style.color = 'green';
                }
            });
        }
    };
    tdNom.appendChild(inputNom);
    tr.appendChild(tdNom);

    // url (editable)
    const tdUrl = document.createElement('td');
    const inputUrl = document.createElement('input');
    inputUrl.type = 'url';
    inputUrl.value = element.url || '';
    inputUrl.dataset.old = element.url || '';
    inputUrl.onchange = function () {
        if (this.value !== this.dataset.old) {
            appelAjax({
                url: '/ajax/modifier.php',
                data: {
                    table: 'partenaire',
                    id: id,
                    lesValeurs: JSON.stringify({url: this.value})
                },
                success: () => {
                    this.dataset.old = this.value;
                    this.style.color = 'green';
                }
            });
        }
    };
    tdUrl.appendChild(inputUrl);
    tr.appendChild(tdUrl);

    // actions
    const tdActions = document.createElement('td');
    tdActions.style.textAlign = 'center';
    const container = document.createElement('div');
    container.style.display = 'flex';
    container.style.gap = '8px';
    container.style.justifyContent = 'center';

    const supprimer = () => appelAjax({
        url: '/ajax/partenaire_supprimer.php',
        data: {id: id},
        success: () => {
            tr.remove();
            nb.innerText = parseInt(nb.innerText) - 1;
            messageBox('Partenaire supprimé');
        }
    });

    const btnSupprimer = creerBoutonSuppression(() => confirmer(supprimer));
    container.appendChild(btnSupprimer);

    const btnModifier = creerBoutonModification(() => window.location.href = 'modifier.html?id=' + id);
    container.appendChild(btnModifier);

    tdActions.appendChild(container);
    tr.appendChild(tdActions);

    lesLignes.appendChild(tr);
}
