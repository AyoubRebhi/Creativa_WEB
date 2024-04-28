<?php

namespace App\Controller;

use App\Entity\Jaime;
use App\Entity\Projet;
use App\Repository\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Twig\JaimeExtension;
use Twig\Environment;

class JaimeController extends AbstractController
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    #[Route('/jaime/add/{idProjet}', name: 'app_jaime_add', methods: ['POST'])]
    public function addJaime(int $idProjet, EntityManagerInterface $entityManager, ProjetRepository $projetRepository, MailerInterface $mailer): Response
    {
        // Set the userId to 28
        $userId = 66;

        // Create a new Jaime object
        $jaime = new Jaime();
        $jaime->setIdUser($userId);
        $jaime->setIdProjet($idProjet);

        // Persist and flush the Jaime object to the database
        $entityManager->persist($jaime);
        $entityManager->flush();

        // Get the count of "jaime" for the project using Twig function
        $jaimeCount = $this->twig->getFunction('getJaimeCountForProject')->getCallable()($idProjet);

        // If the count is 5, send an email
        if ($jaimeCount == 2) {
            // Fetch the project details to pass to the email
            $projectDetails = $entityManager->getRepository(Projet::class)->find($idProjet);
            $this->sendJaimeEmail($projectDetails, $mailer);
        }

        // Redirect back to the project page
        return $this->redirectToRoute('app_projet_show_client', ['idProjet' => $idProjet]);
    }

    private function sendJaimeEmail(Projet $project, MailerInterface $mailer)
    {
        $email = (new Email())
            ->from($project->getUser()->getEmail())
            ->to('rebhi.ayoub@esprit.tn')
            ->subject('Congratulations! Your project has received 5 likes.')
            ->text('Your project "' . $project->getTitre() . '" has received 5 likes.');

        $mailer->send($email);
    }
}
