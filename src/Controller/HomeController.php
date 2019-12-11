<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use App\Repository\TricksRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{

    /**
     * Show the home page
     *
     * @Route("/home", name="app_home")
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

    /**
     * Get the 15 next tricks in the database and create a Twig file with them that will be displayed via Javascript
     *
     * @Route("/home/{start}", name="app_loadMoreTricks", requirements={"start": "\d+"})
     * @param TricksRepository $repo
     * @param int $start
     * @return Response
     */
    public function loadMoreTricks(TricksRepository $repo, $start = 5)
    {
        $tricks = $repo->findBy([], ['create_at' => 'DESC'], 5, $start);

        return $this->render('PublicSide/home/loadMoreTricks.html.twig', [
            'tricks' => $tricks
        ]);
    }

}