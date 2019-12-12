<?php

namespace App\Services;

use App\Entity\Picture;
use App\Entity\Tricks;

class PicturesUploader {

    /**
     * Créer un nom et un path pour l'image et l'enregistre sur le disque
     *
     * @param Picture $picture
     * @param string $nametricks
     * @return Picture $picture
     */
    public function saveImage(Picture $picture, string $nametricks): Picture
    {
        // Récupère le fichier de l'image uploadée
        $file = $picture->getFilepicture();
        // Créer un nom unique pour le fichier
        $name = md5(uniqid()) . '.' . $file->guessExtension();
        // Déplace le fichier
        $path = 'img/tricks/'. $nametricks .'/';
        $file->move($path, $name);

        // Donner le path et le nom au fichier dans la base de données
        $picture->setUrl('/img/tricks/'. $nametricks .'/' . $name);

        return $picture;
    }

    /**
     * Créer un nom et un path pour l'image et l'enregistre sur le disque
     *
     * @param Tricks $picture
     * @return Tricks $picture
     */
    public function saveMainPicture(Tricks $picture): Tricks
    {
        // Récupère le fichier de l'image uploadée
        $file = $picture->getFile();
        // Créer un nom unique pour le fichier
        $name = md5(uniqid()) . '.' . $file->guessExtension();
        // Déplace le fichier
        $path = 'img/tricks/' . $picture->getName() .'/';
        $file->move($path, $name);

        // Donner le path et le nom au fichier dans la base de données
        $picture->setMainPicture('/img/tricks/' . $picture->getName() .'/'.$name);

        return $picture;
    }

}