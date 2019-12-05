<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Form\UpdateFormType;
use App\Repository\UsersRepository;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class UsersController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setImage('/img/pp/default/default.png');
            $user->setLevelAdministration(['ROLE_USER']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('PublicSide/users/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/{username}", name="app_profile")
     * @param UsersRepository $users
     * @param $username
     * @return Response
     */
    public function profile(UsersRepository $users, $username): Response
    {
        $user_profile = $users->findFiveProfile($username);

        return $this->render('PublicSide/users/profile.html.twig', ['user' => $user_profile]);
    }

    /**
     * @Route("/settings", name="app_settings", methods="GET|POST", options={"expose"=true})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Security $username
     * @return Response
     */
    public function settings(Request $request, UserPasswordEncoderInterface $passwordEncoder, Security $username): Response
    {

        if ($username->getUser() === null) {

            $user = new Users();

            $repository = $this->getDoctrine()->getRepository(Users::class);
            $result = $repository->findOneBy(array('username' => $username->getUser()->getUsername()));

            $form_update = $this->createForm(UpdateFormType::class, $user);
            $form_update->handleRequest($request);

            if ($request->isXmlHttpRequest()) {
                $result->setPassword(
                    $passwordEncoder->encodePassword($user,
                        $form_update->get('plainPassword')->getData()
                    )
                );

                $result->setEmail($form_update->get('email')->getData());

                $file = $_FILES['file'];

                $file = new UploadedFile($file['tmp_name'], $file['name'], $file['type']);
                $filename = $this->generateUniqueName() . '.' . $file->guessExtension();
                $file->move(
                    $this->getTargetDir() . $username->getUser()->getUsername() . '/',
                    $filename
                );

                unlink(substr($username->getUser()->getImage(), 1));

                $result->setImage('/img/pp/users/' . $username->getUser()->getUsername() . '/' . $filename);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($result);
                $entityManager->flush();


                return new JsonResponse('redirect');
            }

            return $this->render('PublicSide/users/settings.html.twig', [
                'user' => $result,
                'registrationForm' => $form_update->createView()
            ]);
        }
        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/forgotten ", name="app_forgotten")
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @return Response
     */
    public function forgottenPassword(
        Request $request,
        Swift_Mailer $mailer,
        TokenGeneratorInterface $tokenGenerator
    ): Response
    {

        if ($request->isMethod('POST')) {

            $email = $request->request->get('email');

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(Users::class)->findOneByEmail($email);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('app_home');
            }
            $token = $tokenGenerator->generateToken();

            try {
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('app_home');
            }

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Forgot Password'))
                ->setSubject('Reset password')
                ->setFrom('d.valade87@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    "Voici le token pour reset votre mot de passe : " . $url,
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash('notice', 'Mail envoyé');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('PublicSide/users/forgotten.html.twig');
    }

    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     * @param Request $request
     * @param string $token
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $entityManager->getRepository(Users::class)->findOneBy(["reset_token" => $token]);

            if ($user === null) {
                $this->addFlash('danger', 'Token Inconnu');
                return $this->redirectToRoute('app_home');
            }

            $user->setResetToken(null);

            if($request->request->get('password') == $request->request->get('password2')){
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
                $entityManager->flush();

                $this->addFlash('notice', 'Mot de passe mis à jour');

                return $this->redirectToRoute('app_home');
            }
        }
            return $this->render('PublicSide/users/reset.html.twig', ['token' => $token]);
    }
}