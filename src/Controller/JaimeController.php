<?php

namespace App\Controller;

use App\Entity\Jaime;
use App\Form\JaimeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/jaime')]
class JaimeController extends AbstractController
{
    #[Route('/', name: 'app_jaime_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $jaimes = $entityManager
            ->getRepository(Jaime::class)
            ->findAll();

        return $this->render('jaime/index.html.twig', [
            'jaimes' => $jaimes,
        ]);
    }
}
