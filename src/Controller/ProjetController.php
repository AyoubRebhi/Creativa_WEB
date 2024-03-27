<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProjetRepository;

class ProjetController extends AbstractController
{
    #[Route('/projet', name: 'app_projet')]
    public function index(): Response
    {
        return $this->render('projet/index.html.twig', [
            'controller_name' => 'ProjetController',
        ]);
    }

    #[Route('/read', name: 'read_projet')]
    public function read(ProjetRepository $repo): Response
    {
        $projets = $repo->findAll();
        return $this->render('projet/read.html.twig', [
            'projets' => $projets,
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return new Response("about page");
    }
}
