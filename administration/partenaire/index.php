<?php
require $_SERVER['DOCUMENT_ROOT'] . '/include/autoload.php';

// récupération des partenaires
$data = json_encode(Partenaire::getAll());

// transmission des données à l'interface
$head = <<<HTML
    <script>
        const lesPartenaires = $data;
    </script>
HTML;


// chargement de l'interface
require RACINE . "/include/interface.php";
