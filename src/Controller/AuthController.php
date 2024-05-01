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
use Symfony\Component\Security\Core\Security;
use App\Repository\UserRepository;


class AuthController extends AbstractController
{


    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/auth", name="app_auth")
     */
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'AuthController',
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

        if ($form->isSubmitted()) {
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
            if ($user->getPassword() !== $form->get('passwordConfirmation')->getData()) {
                $form->get('passwordConfirmation')->addError(new FormError('Les mots de passe ne correspondent pas.'));
            }



            if ($form->getErrors(true)->count() === 0) {
                // Encodage du mot de passe avant de l'enregistrer
                $user->setPassword(password_hash($user->getPassword(), PASSWORD_BCRYPT));

                // Enregistrement de l'utilisateur
                $entityManager->persist($user);
                $entityManager->flush();

                // Redirection vers une page de confirmation ou une autre page après l'enregistrement
                return $this->redirectToRoute('app_login');
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
        return $this->forward('App\Controller\SecurityController::login');
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
     * @Route("/userpage1", name="userpage1")
     */
    public function directtouser(): Response
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/Adminpage", name="Adminpage")
     */
    public function directtoAdmin(): Response
    {
        return $this->render('backOfficeAdmin.html.twig');
    }
    /**
     * @Route("/Artistpage", name="Artistpage")
     */
    public function directtoArtist(): Response
    {
        return $this->render('backOffice.html.twig');
    }




    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(SessionInterface $session): response
    {
        $session->clear();
        return $this->forward('App\Controller\AuthController::login');
    }


    /**
     * @Route("/roles", name="statistiques_roles")
     */
    public function roles(UserRepository $userRepository): Response
    {
        // Récupérer les données de statistiques sur les utilisateurs par rôle
        $stats = $userRepository->countUsersByRole();

        // Préparer les données pour l'affichage dans la vue
        $roleLabels = [];
        $userCounts = [];

        foreach ($stats as $stat) {
            $roleLabels[] = $stat['role'];
            $userCounts[] = $stat['userCount'];
        }

        // Rendre la vue avec les données de statistiques
        return $this->render('user/roles.html.twig', [
            'roleLabels' => $roleLabels,
            'userCounts' => $userCounts,
        ]);
    }
}
