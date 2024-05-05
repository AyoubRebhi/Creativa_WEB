<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Comments;
use App\Repository\CommentRepository;
use Doctrine\Persistence\ManagerRegistry;

class CommentController extends AbstractController
{

    #[Route('/afficherComment/{postId}', name: 'afficher_comment')]
    function affiche(CommentRepository $repo, $postId)
    {
        $posts = $repo->findBy(['postId' => $postId]);

        return $this->render('post/afficherComment.html.twig', ['o1' => $posts]);
    }
}
