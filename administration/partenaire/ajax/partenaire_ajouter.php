<?php
declare(strict_types=1);

require $_SERVER['DOCUMENT_ROOT'] . '/include/autoload.php';

// Ajout d'un partenaire : attend nom, url et éventuel fichier 'fichier'
$table = new Partenaire();

if (!$table->donneesTransmises()) {
    echo json_encode(['error' => $table->getLesErreurs()], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!$table->checkAll()) {
    echo json_encode(['error' => $table->getLesErreurs()], JSON_UNESCAPED_UNICODE);
    exit;
}

$table->insert();

echo json_encode(['success' => $table->getLastInsertId()], JSON_UNESCAPED_UNICODE);
