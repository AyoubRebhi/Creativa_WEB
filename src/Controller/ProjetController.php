<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\User;
use App\Form\Projet1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


#[Route('/projet')]
class ProjetController extends AbstractController
{
    private $security;
    private $session;


    public function __construct(SessionInterface $session, Security $security)
    {
        $this->security = $security;
        $this->session = $session;
    }

    #[Route('/admin', name: 'app_projet_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $projets = $entityManager
            ->getRepository(Projet::class)
            ->findAll();

        return $this->render('projet/index.html.twig', [
            'projets' => $projets,
        ]);
    }
    #[Route('/artist', name: 'app_projet_indexArtist', methods: ['GET'])]
    public function indexArtist(EntityManagerInterface $entityManager): Response
    {
        $idUser = $this->session->get('user_id');
        $projets = $entityManager
            ->getRepository(Projet::class)
            ->findBy(['user' => $idUser]);

        return $this->render('projet/indexArtist.html.twig', [
            'projets' => $projets,
        ]);
    }

    #[Route('/client', name: 'app_projet_indexClient', methods: ['GET'])]
    public function indexClient(EntityManagerInterface $entityManager): Response
    {
        $projets = $entityManager
            ->getRepository(Projet::class)
            ->findBy(['isvisible' => true]);

        return $this->render('projet/indexClient.html.twig', [
            'projets' => $projets,
        ]);
    }
    #[Route('/home1', name: 'app_projet_home', methods: ['GET'])]
    public function acceuil(): Response
    {


        return $this->render('projet/home.html.twig');
    }


    #[Route('/new', name: 'app_projet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $idUser = $this->session->get('user_id');
        // Fetch the User entity based on the provided idUser
        $user = $this->getDoctrine()->getRepository(User::class)->find($idUser);

        // Check if the user exists
        if (!$user) {
            // Handle the case when the user does not exist
            throw $this->createNotFoundException('User not found');
        }

        $projet = new Projet();
        $form = $this->createForm(Projet1Type::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $media */
            $media = $form['media']->getData();
            if ($media) {
                // Call the upload function to handle file upload
                $fileName = $this->upload($media);

                // Check if file upload was successful
                if (!$fileName) {
                    return new Response('Failed to upload the media.', Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                // Set the media path in the projet entity
                $projet->setMedia($fileName);
            }

            // Set the fetched user as the owner of the projet
            $projet->setUser($user);

            // Call lifecycle callbacks to set createdAt and updatedAt
            $projet->setTimestampsOnCreate();

            $entityManager->persist($projet);
            $entityManager->flush();

            return $this->redirectToRoute('app_projet_indexArtist', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('projet/new.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    private function upload($media): ?string
    {
        $fileName = null;

        try {
            // Generate a unique file name
            $fileName = uniqid() . '.' . $media->guessExtension();

            // Move the uploaded file to the designated directory
            $media->move($this->getParameter('media_dir'), $fileName);
        } catch (FileException $e) {
            // Log the error or handle it as needed
            return null;
        }

        return $fileName;
    }


    #[Route('/admin/show/{idProjet}', name: 'app_projet_show', methods: ['GET'])]
    public function show(Projet $projet): Response
    {
        return $this->render('projet/show.html.twig', [
            'projet' => $projet,
        ]);
    }
    #[Route('/artist/show/{idProjet}', name: 'app_projet_showArtist', methods: ['GET'])]
    public function showArtist(Projet $projet): Response
    {
        return $this->render('projet/showArtist.html.twig', [
            'projet' => $projet,
        ]);
    }

    #[Route('/client/{idProjet}', name: 'app_projet_show_client', methods: ['GET'])]
    public function showClient(Projet $projet): Response
    {
        $idUser = $this->session->get('user_id');
        $user = $projet->getUser();
        return $this->render('projet/showClient.html.twig', [
            'projet' => $projet,
            'user' => $user,
            'idUser' => $idUser,
        ]);
    }
    #[Route('/artist/{idProjet}/edit', name: 'app_projet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Projet1Type::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $media */
            $media = $form['media']->getData();
            if ($media) {
                $fileName = uniqid() . '.' . $media->guessExtension();

                try {
                    $media->move($this->getParameter('media_dir'), $fileName);
                    $projet->setMedia($fileName);
                } catch (FileException $e) {
                    // Handle file exception
                    return new Response('Échec du téléchargement du média.', Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_projet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('projet/edit.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }


    #[Route('/{idProjet}', name: 'app_projet_delete', methods: ['POST'])]
    public function delete(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $projet->getIdProjet(), $request->request->get('_token'))) {
            $entityManager->remove($projet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_projet_indexArtist', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{idProjet}/toggle-status', name: 'app_projet_toggle_status', methods: ['POST'])]
    public function toggleStatus(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        // Check if the CSRF token is valid
        if ($this->isCsrfTokenValid('toggle_status' . $projet->getIdProjet(), $request->request->get('_token'))) {
            // Toggle the visibility status
            $projet->changeStatus();

            // Persist the changes to the database
            $entityManager->flush();
        }

        // Redirect back to the projet index page
        return $this->redirectToRoute('app_projet_index');
    }
}
