<?php
require $_SERVER['DOCUMENT_ROOT'] . '/include/autoload.php';

$rows = Partenaire::getAll();

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['rows' => $rows], JSON_UNESCAPED_UNICODE);
