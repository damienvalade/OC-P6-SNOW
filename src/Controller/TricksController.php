<?php


namespace App\Controller;


use App\Entity\Commentaries;
use App\Entity\Tricks;
use App\Entity\TypeTricks;
use App\Form\CommentaryType;
use App\Form\TrickType;
use App\Repository\TricksRepository;
use App\Repository\TypeTricksRepository;
use App\Service\UploadImage;
use App\Services\PicturesUploader;
use Cassandra\Date;
use Doctrine\Common\Persistence\ObjectManager;
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
     * Get the 5 next comments in the database and create a Twig file with them that will be displayed via Javascript
     *
     * @Route("/trick/{tricks}/{start}", name="loadMoreComments", requirements={"start": "\d+"})
     * @param TricksRepository $repo
     * @param $tricks
     * @param int $start
     * @return Response
     */
    public function loadMoreComments(TricksRepository $repo, $tricks, $start = 5)
    {
        $tricks = $repo->findOneBy(array('name' => $tricks));

        return $this->render('PublicSide/tricks/loadMore.html.twig', [
            'tricks' => $tricks,
            'start' => $start
        ]);
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
     * @param PicturesUploader $uploadImage
     * @param Security $username
     * @return Response
     * @throws Exception
     */
    public function add(Request $request,PicturesUploader $uploadImage , Security $username): Response
    {
        $tricks = new Tricks();

        $form = $this->createForm(TrickType::class, $tricks);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $entityManager = $this->getDoctrine()->getManager();

            $image = $form['file']->getData();

            $mainImage = $tricks->setFile($image);
            $mainImage = $uploadImage->saveMainPicture($mainImage);
            $entityManager->persist($mainImage);

            foreach($tricks->getPictures() as $picture)
            {
                $picture->setTricks($tricks);
                $picture = $uploadImage->saveImage($picture, $tricks->getName());
                $entityManager->persist($picture);
            }

            foreach($tricks->getVideos() as $video)
            {
                $video->setTricks($tricks);
                $entityManager->persist($video);
            }

            $tricks->setUsers($username->getUser());
            $tricks->setName($form->get('name')->getData());
            $tricks->setTypeTricks($form->get('type_tricks')->getData());
            $tricks->setDescription($form->get('description')->getData());
            $tricks->setCreateAt(new \DateTime());


            $entityManager->persist($tricks);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('PublicSide/tricks/addTricks.html.twig', [
            'form' => $form->createView()
        ]);

    }
}