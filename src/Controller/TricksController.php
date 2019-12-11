<?php


namespace App\Controller;


use App\Entity\Commentaries;
use App\Entity\Tricks;
use App\Entity\TypeTricks;
use App\Form\CommentaryType;
use App\Form\TrickType;
use App\Repository\TricksRepository;
use App\Repository\TypeTricksRepository;
use Cassandra\Date;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;


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

        $tricks = $trick->paginator($page,'5');

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

    /**
     * @Route("/showtricks/{tricks}", name="app_showtricks")
     * @param TricksRepository $tricksRepo
     * @param $tricks
     * @param Request $request
     * @param Security $username
     * @return Response
     * @throws Exception
     */
    public function profile(TricksRepository $tricksRepo, $tricks,Request $request, Security $username): Response
    {
        $data = $tricksRepo->findOneBy(array('name' => $tricks));

        $commentaries = new Commentaries();
        $form = $this->createForm(CommentaryType::class, $commentaries);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $commentaries->setUsers($username->getUser());
            $commentaries->setTricks($data);
            $commentaries->setCreateAt(new \DateTime());
            $commentaries->setCommentarie($form->get('commentarie')->getData());
            $commentaries->setIsValide(1);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaries);
            $entityManager->flush();
        }

        if ($request->request->get('delete') !== null) {
            $repository = $this->getDoctrine()->getRepository(Commentaries::class);
            $result = $repository->findOneBy(array('id' => $request->request->get('delete')));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($result);
            $entityManager->flush();

            return $this->redirectToRoute('app_showtricks', array('tricks' => $tricks));
        }

        return $this->render('PublicSide/tricks/showTricks.html.twig', ['tricks' => $data,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/edittricks/{id_tricks}", name="app_edittricks")
     * @param TricksRepository $tricksRepo
     * @param $id_tricks
     * @return Response
     */
    public function edit(TricksRepository $tricksRepo, $id_tricks): Response
    {
        $data = $tricksRepo->findOneBy(array('id' => $id_tricks));

        return $this->render('PublicSide/tricks/editTricks.html.twig', ['tricks' => $data]);
    }

    /**
     * @Route("/deletetricks", name="app_deletetricks")
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request): Response
    {

        $repository = $this->getDoctrine()->getRepository(Tricks::class);
        $result = $repository->findOneBy(array('id' => $request->request->get('delete')));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($result);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');

    }

    /**
     * @Route("/addtricks", name="app_addtricks")
     * @param Request $request
     * @param Security $username
     * @return Response
     * @throws Exception
     */
    public function add(Request $request, Security $username): Response
    {
        $tricks = new Tricks();

        $form = $this->createForm(TrickType::class, $tricks);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $tricks->setUsers($username->getUser());
            $tricks->setName($form->get('name')->getData());
            $tricks->setTypeTricks($form->get('type_tricks')->getData());
            $tricks->setDescription($form->get('description')->getData());
            $tricks->setMainPicture($form->get('main_picture')->getData());
            $tricks->setCreateAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tricks);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('PublicSide/tricks/addTricks.html.twig', [
            'form' => $form->createView()
        ]);

    }
}