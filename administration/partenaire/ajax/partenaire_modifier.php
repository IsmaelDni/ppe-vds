<?php
declare(strict_types=1);

require $_SERVER['DOCUMENT_ROOT'] . '/include/autoload.php';

if (!Std::existe('id', 'lesValeurs')) {
    Erreur::envoyerReponse('Tous les paramètres attendus ne sont pas transmis', 'global');
}

$id = $_POST['id'];
$lesValeurs = json_decode($_POST['lesValeurs'], true);

if (!is_array($lesValeurs)) {
    Erreur::envoyerReponse('Données mal formées', 'global');
}

$table = new Partenaire();
$table->update($id, $lesValeurs);

echo json_encode(['success' => 'Opération réalisée avec succès'], JSON_UNESCAPED_UNICODE);
