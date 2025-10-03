<?php
require $_SERVER['DOCUMENT_ROOT'] . '/include/autoload.php';

if (!Std::existe('id')) {
    Erreur::envoyerReponse('Identifiant manquant', 'global');
}

$id = $_POST['id'];
$table = new Partenaire();
$table->delete($id);

echo json_encode(['success' => 'Opération réalisée avec succès'], JSON_UNESCAPED_UNICODE);
