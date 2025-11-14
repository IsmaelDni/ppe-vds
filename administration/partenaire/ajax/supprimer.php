<?php
// activation du chargement dynamique des ressources
require $_SERVER['DOCUMENT_ROOT'] . '/include/autoload.php';

// Contrôle de l'existence du paramètre attendu : id
if (!isset($_POST['id'])) {
    Erreur::envoyerReponse("Paramètre manquant", 'global');
}

$id = $_POST['id'];

// vérification de l'existence du classement:
$ligne = partenaire::getById($id);
if (!$ligne) {
    Erreur::envoyerReponse("Ce classement: n'existe pas", 'global');
}

// suppression de l'enregistrement en base de données
Partenaire::supprimer($id);

Partenaire::supprimerFichier($ligne['fichier']);


$reponse = ['success' => "Le classement: a été supprimé"];
echo json_encode($reponse, JSON_UNESCAPED_UNICODE);
