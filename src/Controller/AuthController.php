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
use Symfony\Component\Form\FormError;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class AuthController extends AbstractController
{
    #[Route('/auth', name: 'app_auth')]
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }


     /**
     * @Route("/login", name="login")
     */
    public function login(Request $request,SessionInterface $session): Response
    {
        $form = $this->createForm(AuthFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $user = $form->getData(); // Récupérer l'objet User du formulaire
            $email = $user->getEmail(); // Accéder à l'email de l'utilisateur
            $password = $user->getPassword();

            $userRepository = $this->getDoctrine()->getRepository(User::class);
            $isAuthenticated = $userRepository->findUserByEmailAndPassword($email, $password);

            if ($isAuthenticated) {
                // If authenticated, retrieve the user object separately
                $authenticatedUser = $userRepository->findOneByEmail($email);
                $userId = $authenticatedUser->getIdUser();
                $session->set('user_id', $userId);
    
    
                // Check the role of the authenticated user
                $role = $authenticatedUser->getRole();
    
                if ($role === 'ADMIN') {
                    // Redirect admin user to userpage
                    return $this->redirectToRoute('userpage');
                } else {
                    // Redirect other users to homepage or another appropriate page
                    return $this->redirectToRoute('homepage');
                }
            } else {
                // Authentication failed, display an error message to the user
                $form->get('email')->addError(new FormError('les informations sont incorrects.'));
            }
        }

        return $this->render('auth/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

// /**
//      * @Route("/register", name="user_register")
//      */
//     public function register(Request $request, EntityManagerInterface $entityManager): Response
//     {
//         $user = new User();
//         $form = $this->createForm(InscriptionFormType::class, $user);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             // Encodez le mot de passe avant de l'enregistrer

//             $entityManager->persist($user);
//             $entityManager->flush();

//             // Rediriger vers une page de confirmation ou une autre page après l'enregistrement
//             return $this->redirectToRoute('registration_success');
//         }

//         return $this->render('auth/auth.html.twig', [
//             'form' => $form->createView(),
//         ]);
//     }
     /**
     * @Route("/register", name="user_register")
     */

     public function register1(Request $request, EntityManagerInterface $entityManager): Response
     {
         $user = new User();
         $form = $this->createForm(InscriptionFormType::class, $user);
         $form->handleRequest($request);
 
         if ($form->isSubmitted() ) {
             // Vérifier l'unicité du nom d'utilisateur
             $existingUsername = $entityManager->getRepository(User::class)->findOneBy(['username' => $user->getUsername()]);
             if ($existingUsername) {
                 $form->get('username')->addError(new FormError('Ce nom d\'utilisateur est déjà utilisé.'));
             }
 
             // Vérifier l'unicité de l'email
             $existingEmail = $entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
             if ($existingEmail) {
                 $form->get('email')->addError(new FormError('Cet email est déjà utilisé.'));
             }
 
             if ($form->getErrors(true)->count() === 0) {
                 // Encodage du mot de passe avant de l'enregistrer
                 $user->setPassword(password_hash($user->getPassword(), PASSWORD_BCRYPT));
 
                 // Enregistrement de l'utilisateur
                 $entityManager->persist($user);
                 $entityManager->flush();
 
                 // Redirection vers une page de confirmation ou une autre page après l'enregistrement
                 return $this->redirectToRoute('registration_success');
             }
         }
 
         // Affichage du formulaire avec les erreurs
         return $this->render('auth/auth.html.twig', [
             'form' => $form->createView(),
         ]);
     }



    /**
     * @Route("/registration/success", name="registration_success")
     */
    public function registrationSuccess(): Response
    {
        return $this->forward('App\Controller\AuthController::login');
    }


    /**
     * @Route("/userpage", name="userpage")
     */
    public function directto(): Response
    {
        return $this->forward('App\Controller\UserController::index');
    }
/**
     * @Route("/homepage", name="homepage")
     */
    public function directto1(): Response
    {
        return $this->forward('App\Controller\AuthController::index');
    }

/**
     * @Route("/app_login", name="app_login")
     */
    public function loginpage(): response
    {
        return $this->forward('App\Controller\AuthController::login');
    }


    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(SessionInterface $session): Response
    {        $session->clear();
    }
    

}

