<?php

namespace App\Controller;

use App\Entity\Commentaries;
use App\Entity\Users;
use App\Form\UpdateRoleType;
use App\Repository\CommentariesRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AdminController extends AbstractController
{
    /**
     * @Route("/usersadministration", name="app_usersadministration")
     * @param UsersRepository $users
     * @param Request $request
     * @param Security $user
     * @return Response
     */
    public function userAdministration(UsersRepository $users, Request $request, Security $user): Response
    {
        if ($user->isGranted('ROLE_ADMIN')) {
            $user_profile = $users->getAll();
            $form_update = $this->createForm(UpdateRoleType::class, $user_profile);
            $form_update->handleRequest($request);
            if ($form_update->isSubmitted() && $form_update->isValid()) {

                $repository = $this->getDoctrine()->getRepository(Users::class);

                if ($request->request->get('update') !== null) {
                    $post = explode('%', $request->request->get('update'));
                    $userName = $post[0];
                    $inputName = $post[1];
                    $result = $repository->findOneBy(array('username' => $userName));
                    $Role = $form_update->get((string)$inputName)->getData();
                    $result->setRoles([$Role]);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($result);
                    $entityManager->flush();
                }
                if ($request->request->get('delete') !== null) {
                    $result = $repository->findOneBy(array('username' => $request->request->get('delete')));
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($result);
                    $entityManager->flush();
                }
                return $this->redirectToRoute('app_usersadministration');
            }
            return $this->render('AdminSide/admin/usersManager.html.twig', [
                'user' => $user_profile,
                'registrationForm' => $form_update->createView()
            ]);
        }
        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/commenatariesadministration", name="app_commenatariesadministration")
     * @param CommentariesRepository $commentaries
     * @param Request $request
     * @return Response
     */

    public function commentariesAdministration(CommentariesRepository $commentaries, Request $request)
    {

        $commentaries = $commentaries->findBy(array('is_valide' => '0'));

        if ($request->request->get('valid') || $request->request->get('delete')) {

            $repository = $this->getDoctrine()->getRepository(Commentaries::class);

            if ($request->request->get('valid') !== null) {

                $result = $repository->findOneBy(array('id' => $request->request->get('valid')));
                $result->setIsvalide(true);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($result);
                $entityManager->flush();
            }

            if ($request->request->get('delete') !== null) {
                $result = $repository->findOneBy(array('id' => $request->request->get('delete')));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($result);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_commenatariesadministration');
        }


        return $this->render('AdminSide/admin/commentariesManager.html.twig', [
            'commentaries' => $commentaries
        ]);

    }
}