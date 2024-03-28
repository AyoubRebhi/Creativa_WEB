<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AuthFormType;
use App\Form\InscriptionFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class AuthController extends AbstractController
{
    #[Route('/auth', name: 'app_auth')]
    public function index(): Response
    {
        return $this->render('auth/index.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }


     /**
     * @Route("/login", name="login")
     */
    public function login(Request $request): Response
    {
        $form = $this->createForm(AuthFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData(); // Récupérer l'objet User du formulaire
            $email = $user->getEmail(); // Accéder à l'email de l'utilisateur
            $password = $user->getPassword();

            $userRepository = $this->getDoctrine()->getRepository(User::class);
            $isAuthenticated = $userRepository->findUserByEmailAndPassword($email, $password);

            if ($isAuthenticated) {
                // L'utilisateur est authentifié, redirigez-le vers la page d'accueil ou une autre page sécurisée
                return $this->render('auth/homepage.html.twig');
            } else {
                // L'authentification a échoué, affichez un message d'erreur à l'utilisateur
                $this->addFlash('error', 'Invalid email or password.');
            }
        }

        return $this->render('auth/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

/**
     * @Route("/register", name="user_register")
     */
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(InscriptionFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encodez le mot de passe avant de l'enregistrer
            $user->setPassword(password_hash($user->getPassword(), PASSWORD_BCRYPT));

            $entityManager->persist($user);
            $entityManager->flush();

            // Rediriger vers une page de confirmation ou une autre page après l'enregistrement
            return $this->redirectToRoute('registration_success');
        }

        return $this->render('auth/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/registration/success", name="registration_success")
     */
    public function registrationSuccess(): Response
    {
        return $this->render('auth/index.html.twig');
    }

}
