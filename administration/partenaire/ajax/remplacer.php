<?php
// activation du chargement dynamique des ressources
require $_SERVER['DOCUMENT_ROOT'] . '/include/autoload.php';

// contrôle de la présence du fichier transmis
if (!isset($_FILES['fichier']) || $_FILES['fichier']['error'] !== UPLOAD_ERR_OK) {
    Erreur::envoyerReponse("Le logo du partenaire n'est pas transmis ou l'upload a échoué", 'global');
}

// vérification du paramètre id :
if (!isset($_POST['id']) || empty($_POST['id'])) {
    Erreur::envoyerReponse("L'identifiant du partenaire n'est pas transmis", 'global');
}

// récupération et validation basique de l'id
$id = (int) $_POST['id'];
if ($id <= 0) {
    Erreur::envoyerReponse("Identifiant invalide", 'global');
}

// Récupération de la configuration et dossier
$config = Partenaire::getConfig();
$maxSize = $config['maxSize'] ?? 150 * 1024;
$allowedExt = $config['extensions'] ?? ['jpg','png'];
$allowedTypes = $config['types'] ?? ['image/jpeg','image/png'];
$repertoire = isset($config['repertoire']) ? rtrim($config['repertoire'], '/') : '/data/partenaire';
$destDir = RACINE . $repertoire . '/';

// vérification du fichier
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $_FILES['fichier']['tmp_name']);
finfo_close($finfo);

$size = $_FILES['fichier']['size'];
$ext = strtolower(pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION));

if ($size > $maxSize) {
    Erreur::envoyerReponse("Fichier trop volumineux (max " . ($maxSize/1024) . " Ko)", 'global');
}
if (!in_array($ext, $allowedExt, true) || !in_array($mime, $allowedTypes, true)) {
    Erreur::envoyerReponse("Type de fichier non autorisé", 'global');
}

// préparer le nom de destination
$newName = 'partenaire_' . $id . '_' . time() . '.' . $ext;
$destPath = $destDir . $newName;

// créer le répertoire si besoin
if (!is_dir($destDir)) {
    @mkdir($destDir, 0755, true);
}

// suppression de l'ancien fichier si présent
$ancienne = Partenaire::getById($id);
if ($ancienne && !empty($ancienne['fichier'])) {
    $ancienPath = $destDir . $ancienne['fichier'];
    if (is_file($ancienPath)) {
        @unlink($ancienPath);
    }
}

// déplacer le fichier uploadé
if (!move_uploaded_file($_FILES['fichier']['tmp_name'], $destPath)) {
    Erreur::envoyerReponse("Impossible d'enregistrer le fichier", 'global');
}

// mettre à jour la table avec le nouveau nom de fichier
try {
    Partenaire::majLogo($id, $newName);
} catch (Throwable $e) {
    // en cas d'erreur, supprimer le fichier nouvellement déplacé
    if (is_file($destPath)) {
        @unlink($destPath);
    }
    Erreur::envoyerReponse($e->getMessage(), 'global');
}

echo json_encode(['success' => 'Le logo a été enregistré'], JSON_UNESCAPED_UNICODE);
?>