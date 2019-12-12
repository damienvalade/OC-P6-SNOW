<?php

namespace App\Services;

use App\Entity\Picture;
use App\Entity\Tricks;

class PicturesUploader {

    /**
     * Créer un nom et un path pour l'image et l'enregistre sur le disque
     *
     * @param Picture $picture
     * @param string $nameTrick
     * @return Picture $image
     */
    public function saveImage(Picture $picture, string $nameTrick): Picture
    {
        // Récupère le fichier de l'image uploadée
        $file = $picture->getFile();
        // Créer un nom unique pour le fichier
        $name = md5(uniqid()) . '.' . $file->guessExtension();
        // Déplace le fichier
        $path = 'img/tricks/' . $nameTrick .'/';
        $file->move($path, $name);

        // Donner le path et le nom au fichier dans la base de données
        $picture->setUrl($path . $name);

        return $picture;
    }

    /**
     * Créer un nom et un path pour l'image et l'enregistre sur le disque
     *
     * @param Tricks $picture
     * @return Tricks $image
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
        $picture->setMainPicture($path.$name);

        return $picture;
    }

}