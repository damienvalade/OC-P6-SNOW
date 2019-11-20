<?php

namespace App\Controller;

use App\Repository\TricksRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{

    /**
     * Show the home page
     *
     * @Route("", name="app_home")
     * @param TricksRepository $trick
     * @return Response
     */

    public function home(TricksRepository $trick): Response
    {

        $tricks = $trick->findAll();

        return $this->render('PublicSide/home/home.html.twig', [
            'tricks' => $tricks,
        ]);
    }

}