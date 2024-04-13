<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Regex;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
       
        // Récupérer tous les utilisateurs
        $users = $userRepository->findAll();

        // Vérifier si la durée de blocage est dépassée pour chaque utilisateur et le débloquer si nécessaire
        foreach ($users as $user) {
            if ($user->isBlocked() && $user->getBlockEndDate() < new \DateTime()) {
                $user->setBlocked(false);
                $user->setBlockEndDate(null);
                $entityManager->flush();
            }
        }

        // Rendre la vue avec la liste des utilisateurs mise à jour
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);

    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{idUser}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        $role = $user->getRole();

        // Déterminer le template à utiliser en fonction du rôle de l'utilisateur
        switch ($role) {
            case 'CLIENT':
                $editTemplate = 'user/show.html.twig';
                break;
            case 'ARTIST':
                $editTemplate = 'user/profileArtist.html.twig';
                break;
            case 'ADMIN':
                $editTemplate = 'user/profileAdmin.html.twig';
                break;
            default:
                // Rediriger vers une page d'erreur ou la page d'accueil si le rôle n'est pas reconnu
                return $this->redirectToRoute('homepage');
        }

      
        return $this->render($editTemplate, [
            'user' => $user,
        ]);
    }

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/{idUser}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le rôle de l'utilisateur actuel
        $role = $user->getRole();

        // Déterminer le template à utiliser en fonction du rôle de l'utilisateur
        switch ($role) {
            case 'CLIENT':
                $editTemplate = 'user/edit.html.twig';
                break;
            case 'ARTIST':
                $editTemplate = 'user/editArtist.html.twig';
                break;
            case 'ADMIN':
                $editTemplate = 'user/editAdmin.html.twig';
                break;
            default:
                // Rediriger vers une page d'erreur ou la page d'accueil si le rôle n'est pas reconnu
                return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Rediriger l'utilisateur vers la page appropriée après l'édition
            switch ($role) {
                case 'CLIENT':
                    return $this->redirectToRoute('userpage');
                case 'ARTISTE':
                    return $this->redirectToRoute('Artistpage');
                case 'ADMIN':
                    return $this->redirectToRoute('Adminpage');
                default:
                    // Rediriger vers une page d'erreur ou la page d'accueil si le rôle n'est pas reconnu
                    return $this->redirectToRoute('homepage');
            }
        }

        return $this->renderForm($editTemplate, [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{idUser}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getIdUser(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
/**
     * @Route("/saisir-duree/{id}", name="saisir_duree")
     */
    public function saisirDuree(Request $request, $id): Response
    {
        // Récupérer l'utilisateur en fonction de l'ID
        $entityManager = $this->getDoctrine()->getManager();
        $utilisateur = $entityManager->getRepository(User::class)->find($id);

        if (!$utilisateur) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        // Créer un formulaire pour saisir la durée de blocage
        $form = $this->createFormBuilder()
    ->add('duree', TextType::class, [
        'label' => 'Durée en minutes',
        'attr' => ['placeholder' => 'Entrez la durée en minutes'],
        'constraints' => [
            new NotBlank(['message' => 'La durée est requise']),
            new PositiveOrZero(['message' => 'La durée doit être un nombre positif ou zéro']),
            new Regex([
                'pattern' => '/^\d+$/',
                'message' => 'La durée doit être un nombre entier positif',
            ]),
        ],
    ])
    ->add('save', SubmitType::class, ['label' => 'Valider'])
    ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si le formulaire est soumis et valide, traiter les données
            $data = $form->getData();
            $duree = $data['duree'];

            // Mettre à jour l'utilisateur avec la durée de blocage
            $utilisateur->setBlocked(true);
            $dateFinBlocage = new \DateTime();
            $dateFinBlocage->modify("+{$duree} minutes");
            $utilisateur->setBlockEndDate($dateFinBlocage);

            $entityManager->flush();

            // Rediriger vers une autre page ou afficher un message de confirmation
            return $this->redirectToRoute('app_user_index');
        }

        // Afficher le formulaire de saisie de la durée
        return $this->render('user/bloquer.html.twig', [
            'form' => $form->createView(),
        ]);
    }
   /**
     * @Route("/debloquer-utilisateur/{id}", name="debloquer_utilisateur")
     */
    public function debloquerUtilisateur($id): Response
    {
        // Récupérer l'utilisateur en fonction de l'ID
        $entityManager = $this->getDoctrine()->getManager();
        $utilisateur = $entityManager->getRepository(User::class)->find($id);

        if (!$utilisateur) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        // Débloquer l'utilisateur
        $utilisateur->setBlocked(false);
        $utilisateur->setBlockEndDate(null);

        $entityManager->flush();

        // Rediriger vers une autre page ou afficher un message de confirmation
        return $this->redirectToRoute('app_user_index');
    }



}
