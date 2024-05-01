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
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Twig\Environment;

class JaimeController extends AbstractController
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    #[Route('/jaime/add/{idProjet}', name: 'app_jaime_add', methods: ['POST'])]
    public function addJaime(int $idProjet, EntityManagerInterface $entityManager): Response
    {
        // Set the userId to 28
        $userId = 63;

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
        if ($jaimeCount == 6) {
            // Fetch the project details to pass to the email
            $projectDetails = $entityManager->getRepository(Projet::class)->find($idProjet);
            $this->sendJaimeEmail($projectDetails);
        }

        // Redirect back to the project page
        return $this->redirectToRoute('app_projet_show_client', ['idProjet' => $idProjet]);
    }

    private function sendJaimeEmail(Projet $project)
    {
        $transport = Transport::fromDsn('smtp://allahommayarab@gmail.com:ugoqsvujijuszwna@smtp.gmail.com:587');
        $mailer = new Mailer($transport);
        $email = (new Email())
            ->from('allahommayarab@gmail.com')
            //->to($project->getUser()->getEmail())
            ->to('ayoubrebhi1230@gmail.com')
            ->subject('Congratulations! Your project has received 5 likes.')
            ->text('Your project "' . $project->getTitre() . '" has received 5 likes.');

        $mailer->send($email);
    }
}
