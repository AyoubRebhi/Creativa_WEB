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
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class JaimeController extends AbstractController
{
    private $twig;
    private $session;

    public function __construct(Environment $twig, SessionInterface $session)
    {
        $this->twig = $twig;
        $this->session = $session;
    }

    #[Route('/jaime/add/{idProjet}', name: 'app_jaime_add', methods: ['POST'])]
    public function addJaime(int $idProjet, EntityManagerInterface $entityManager): Response
    {
        // Retrieve user ID from session
        $userId = $this->session->get('user_id');

        // Check if user is authenticated
        if (!$userId) {
            // Handle unauthenticated user, maybe redirect to login page
            // For example:
            return $this->redirectToRoute('app_login');
        }

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
        if ($jaimeCount == 5) {
            // Fetch the project details to pass to the email
            $projectDetails = $entityManager->getRepository(Projet::class)->find($idProjet);
            $this->sendJaimeEmail($projectDetails);
        } else if ($jaimeCount == 10) {
            $projectDetails = $entityManager->getRepository(Projet::class)->find($idProjet);
            $this->sendJaimeEmail2($projectDetails);
        } else if ($jaimeCount == 25) {
            $projectDetails = $entityManager->getRepository(Projet::class)->find($idProjet);
            $this->sendJaimeEmail3($projectDetails);
        } else if ($jaimeCount == 50) {
            $projectDetails = $entityManager->getRepository(Projet::class)->find($idProjet);
            $this->sendJaimeEmail4($projectDetails);
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
            ->subject('Congratulations! ')
            ->html("
            <html>
                <body>
                    <h1 style='color: #333;'>Congratulations!</h1>
                    <p>Your project <strong>{$project->getTitre()}</strong> has received 5 likes.</p>
                </body>
            </html>
            ");

        $mailer->send($email);
    }
    private function sendJaimeEmail2(Projet $project)
    {
        $transport = Transport::fromDsn('smtp://allahommayarab@gmail.com:ugoqsvujijuszwna@smtp.gmail.com:587');
        $mailer = new Mailer($transport);
        $email = (new Email())
            ->from('allahommayarab@gmail.com')
            ->to($project->getUser()->getEmail())
            //->to('ayoubrebhi1230@gmail.com')
            ->subject('Congratulations!')
            ->html("
            <html>
                <body>
                    <h1 style='color: #333;'>Congratulations!</h1>
                    <p>Your project <strong>{$project->getTitre()}</strong> has received 10 likes.</p>
                </body>
            </html>
            ");
        $mailer->send($email);
    }
    private function sendJaimeEmail3(Projet $project)
    {
        $transport = Transport::fromDsn('smtp://allahommayarab@gmail.com:ugoqsvujijuszwna@smtp.gmail.com:587');
        $mailer = new Mailer($transport);
        $email = (new Email())
            ->from('allahommayarab@gmail.com')
            ->to($project->getUser()->getEmail())
            //->to('ayoubrebhi1230@gmail.com')
            ->subject('Congratulations! Your project has received 25 likes.')
            ->html("
            <html>
                <body>
                    <h1 style='color: #333;'>Congratulations!</h1>
                    <p>Your project <strong>{$project->getTitre()}</strong> has received 25 likes.</p>
                </body>
            </html>
            ");
        $mailer->send($email);
    }
    private function sendJaimeEmail4(Projet $project)
    {
        $transport = Transport::fromDsn('smtp://allahommayarab@gmail.com:ugoqsvujijuszwna@smtp.gmail.com:587');
        $mailer = new Mailer($transport);
        $email = (new Email())
            ->from('allahommayarab@gmail.com')
            ->to($project->getUser()->getEmail())
            //->to('ayoubrebhi1230@gmail.com')
            ->subject('Congratulations! Your project has received 50 likes.')
            ->html("
            <html>
                <body>
                    <h1 style='color: #333;'>Congratulations!</h1>
                    <p>Your project <strong>{$project->getTitre()}</strong> has received 50 likes.</p>
                </body>
            </html>
            ");
        $mailer->send($email);
    }
}
