<?php

namespace App\Controller;

use App\Entity\Jaime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JaimeController extends AbstractController
{
    #[Route('/jaime/add/{idProjet}', name: 'app_jaime_add', methods: ['POST'])]
    public function addJaime(int $idProjet, EntityManagerInterface $entityManager): Response
    {
        // Set the userId to 28
        $userId = 28;

        // Create a new Jaime object
        $jaime = new Jaime();
        $jaime->setIdUser($userId);
        $jaime->setIdProjet($idProjet);

        // Persist and flush the Jaime object to the database
        $entityManager->persist($jaime);
        $entityManager->flush();

        // Redirect back to the project page
        return $this->redirectToRoute('app_projet_show_client', ['idProjet' => $idProjet]);
    }
}
