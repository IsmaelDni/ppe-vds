<?php
declare(strict_types=1);

class Partenaire extends Table
{
    public function __construct()
    {
        parent::__construct('partenaire');

        // nom
        $input = new InputText();
        $input->Require = true;
        $input->MaxLength = 255;
        $input->MinLength = 2;
        $this->columns['nom'] = $input;

        // url
        $input = new InputUrl();
        $input->Require = false;
        $input->MaxLength = 1024;
        $this->columns['url'] = $input;

        // fichier (logo)
        $config = [
            'repertoire' => '/data/partenaire',
            'extensions' => ['jpg', 'png'],
            'types' => ['image/pjpeg', 'image/jpeg', 'x-png', 'image/png'],
            'maxSize' => 30 * 1024,
            'require' => false,
            'rename' => true,
            'sansAccent' => true,
            'accept' => '.jpg, .png',
            'redimensionner' => false,
            'height' => 100,
            'width' => 0,
            'label' => 'Logo jpg ou png (hauteur max 100px, taille max 30 Ko)'
        ];

        $input = new InputFileImg($config);
        $input->Require = false;
        $input->Rename = true;
        $this->columns['fichier'] = $input;
    }

    public static function getAll(): array
    {
        $sql = "SELECT id, nom, url, fichier FROM partenaire ORDER BY nom";
        $select = new Select();
        return $select->getRows($sql);
    }

    public static function getPartenaire(): string
    {
        $select = new select();
        return $select->getValue("select contenu from page where id = 1;");
    }

}
