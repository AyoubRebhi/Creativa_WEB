<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Inscription;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Formation;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bridge\Twig\Mime\TemplatedEmail ; 
use Symfony\Component\Mime\Address ; 
use Symfony\Component\Mailer\MailerInterface;

class AccueilController extends AbstractController
{
    private $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/', name: 'app_accueil')]
    public function index(Request $request, FormationRepository $formationRepository): Response
    {
        $email = "MayMatri@esprit.tn";
        $formations = $formationRepository->findAll();
        $formations_inscrit = []; // Initialize the array
        $formations_non_inscrit = []; // Initialize the array
    
        foreach ($formations as $formation) {
            $inscriptions = $formation->getInscriptions();
            $is_inscrit = false;
    
            foreach ($inscriptions as $inscription) {
                if ($inscription->getEmail() == $email) {
                    $is_inscrit = true;
                    break;
                }
            }
    
            if ($is_inscrit) {
                $formations_inscrit[] = $formation; // Append to the array
            } else {
                $formations_non_inscrit[] = $formation; // Append to the array
            }
        }
        $email = "MayMatri@esprit.tn";
    
        return $this->render('accueil/index.html.twig', [
            'formations_insc' => $formations_inscrit,
            'formations_non_insc' => $formations_non_inscrit , 
            'email' => $email , 
        ]);
    }
    


    #[Route('/front/inscription/create/{id}', name: 'app_front_inscription_acc')]
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
        $email = (new TemplatedEmail())
        ->from(new Address('no-reply@creativa.tn'))
        ->to('may@esprit.tn')
        ->subject('Inscription à la formation')
        ->htmlTemplate('inscription/email.html.twig')
        ->context([
            'titre' => $formation->getTitre() , 
            'description' => $formation->getTitre(),
            'nb_places' => $formation->getNbPlaces(),
            'prix' => $formation->getPrix(),
            'nom' => $nom,
            'prenom' => $prenom ,
            'user_email' => $email 
        ]);
    

    $this->mailer->send($email);
        // Redirect the user back to the index page
        return new RedirectResponse($urlGenerator->generate('app_accueil'));
    }




    #[Route('/evaluate/{id}', name: 'evaluate')]
    public function evaluate(Request $request, int $id, UrlGeneratorInterface $urlGenerator): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $formation = $entityManager->getRepository(Formation::class)->find($id);
    
        if (!$formation) {
            throw $this->createNotFoundException('Formation not found');
        }
    
        // Assuming the rating is sent as a form parameter named 'rating'
        $rating = $request->request->get('rating'); // Use request->request to access form parameters
        $email = "MayMatri@esprit.tn";
    
        // Update the evaluation attribute with the user's rating
        $evaluation = $formation->getEvaluation() ?? []; // Retrieve existing evaluation or initialize to empty array
        $evaluation[$email] = $rating;
        $formation->setEvaluation($evaluation);
        $entityManager->persist($formation);
        $entityManager->flush();
    
        return new RedirectResponse($urlGenerator->generate('app_accueil'));
    }
}
