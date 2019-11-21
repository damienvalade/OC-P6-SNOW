<?php


namespace App\Controller;


use App\Entity\Tricks;
use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class TricksController extends AbstractController
{

    /**
     * @Route("/trickslist/{page}", name="app_trickslist")
     * @param TricksRepository $trick
     * @param $page
     * @return Response
     */
    public function tricksList(TricksRepository $trick, $page): Response
    {

        if($page === ''){
            $page = 1;
        }

        $tricks = $trick->findAllPagineEtTrie($page,'5');

        $pagination = array(
            'page' => $page,
            'nbPages' => ceil(count($tricks) / '5'),
            'nomRoute' => 'app_trickslist',
            'paramsRoute' => array()
        );

        return $this->render('PublicSide/tricks/listingTricks.html.twig',
            [
                'tricks' => $tricks,
                'pagination' => $pagination
            ]);

    }

}