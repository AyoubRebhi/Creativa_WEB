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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



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

        if ($form->isSubmitted()) {
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
     * @Route("/register", name="user_register1")
     */

public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
{
    $user = new User();
    $form = $this->createForm(InscriptionFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Encodez le mot de passe avant de l'enregistrer
        $hashedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
        
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        // Rediriger vers une page de confirmation ou une autre page après l'enregistrement
        return $this->render('auth/homepage.html.twig'  ) ; }
    return $this->render('auth/register.html.twig', [
        'form' => $form->createView(),
    ]);
}


      /**
     * @Route("/register1", name="user_register1")
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
        return $this->render('auth/login.html.twig');
    }




    
    /**
     * @Route("/register2", name="user_register")
     */
    public function register2(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(InscriptionFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Générer un code de vérification unique
            $verificationCode = random_int(100000, 999999);

            // Envoyer le code de vérification par e-mail
            $email = (new Email())
                ->from('hhajer09@gmail.com')
                ->to($user->getEmail())
                ->subject('Code de vérification')
                ->text('Votre code de vérification est : ' . $verificationCode);

            $mailer->send($email);

            // Stocker le code de vérification en session pour le vérifier plus tard
            $request->getSession()->set('verification_code', $verificationCode);

            // Stocker l'utilisateur en session pour l'ajouter à la base de données après vérification
            $request->getSession()->set('user_to_register', $user);

            // Rediriger vers la page où l'utilisateur saisira le code de vérification
            return $this->redirectToRoute('verification_page');
        }

        // Affichage du formulaire avec les erreurs
        return $this->render('auth/auth.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verification", name="verification_page")
     */
    public function verificationPage(Request $request)
    {
        // Afficher la page où l'utilisateur saisira le code de vérification
        return $this->render('auth/verification.html.twig');
    }

    /**
     * @Route("/verify-code", name="verify_code", methods={"POST"})
     */
    public function verifyCode(Request $request, EntityManagerInterface $entityManager)
    {
        // Récupérer le code de vérification saisi par l'utilisateur
        $verificationCode = $request->request->get('verification_code');
        
        // Récupérer le code de vérification stocké en session
        $storedVerificationCode = $request->getSession()->get('verification_code');

        // Vérifier si les codes de vérification correspondent
        if ($verificationCode == $storedVerificationCode) {
            // Récupérer l'utilisateur à ajouter à la base de données depuis la session
            $user = $request->getSession()->get('user_to_register');

            // Ajouter l'utilisateur à la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Rediriger vers une page de confirmation après l'enregistrement
            return $this->redirectToRoute('login');
        }

        // Rediriger vers la page de vérification avec un message d'erreur
        return $this->redirectToRoute('verification_page', ['error' => 'Le code de vérification est incorrect']);
    }

}
