<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\FormationRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Inscription;
use Symfony\Bridge\Twig\Mime\TemplatedEmail ; 
use Symfony\Component\Mime\Address ; 
use Symfony\Component\Mailer\MailerInterface;
class FrontFormationController extends AbstractController
{
    #[Route('/formation', name: 'app_front_formation')]
    public function index(Request $request, FormationRepository $formationRepository, PaginatorInterface $paginator): Response
    {

        $formations = $formationRepository->findAll();

        $formations = $paginator->paginate(
            $formations, /* query NOT result */
            $request->query->getInt('page', 1),
            2
        );
        return $this->render('front_formation/index.html.twig', [
            'formations' => $formations,
        ]);
    }

    #[Route('/front/inscription/create/{id}', name: 'app_front_inscription_create')]
    public function createInscription(int $id, FormationRepository $formationRepository, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator): RedirectResponse
    {
        // Retrieve the Formation object based on the provided ID
        $formation = $formationRepository->find($id);

        // If the Formation object does not exist, return a 404 response or handle it accordingly
        if (!$formation) {
            throw $this->createNotFoundException('Formation not found');
        }

        // Generate random values for nom, prenom, and email fields
        $nom = "Matri";
        $prenom = "May";
        $email = "MayMatri@esprit.tn";

        // Create a new Inscription object
        $inscription = new Inscription();
        $inscription->setNom($nom);
        $inscription->setPrenom($prenom);
        $inscription->setEmail($email);
        $inscription->setFormation($formation);

        // Set the current date and time
        $inscription->setDateNow(new \DateTime());

        // Persist and flush the Inscription object
        $entityManager->persist($inscription);
        $entityManager->flush();

        // Redirect the user back to the index page
        return new RedirectResponse($urlGenerator->generate('app_front_formation'));
    }
}
