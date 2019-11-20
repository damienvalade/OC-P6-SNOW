<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('namePicture', [$this, 'namePicture']),
        ];
    }

    public function namePicture(string $namePicture, string $size): string
    {
        $namePath = $namePicture;

        $findSpace = strpos($namePicture, ' ');

        if ($findSpace !== FALSE) {
            $namePath = str_replace(' ', '', $namePicture);
            $namePicture = str_replace(' ', '-', $namePicture);
        }

        $namePicture = '/img/tricks/' . $namePath . '/' . $namePicture . '-' . $size . '.jpg';

        return $namePicture;
    }
}