<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TopicType;
use App\Entity\Topic;
use App\Repository\TopicRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\SubmitType;

class TopicController extends AbstractController
{
    #[Route('/topic', name: 'app_topic')]
    public function index(): Response
    {
        return $this->render('topic/index.html.twig', [
            'controller_name' => 'TopicController',
        ]);
    }
    #[Route('/afficherTopic', name: 'afficher_topic')]
    function affiche(TopicRepository $repo)
    {
        $obj = $repo->findAll();
        return $this->render('topic/affichertopic.html.twig', ['o' => $obj]);
    }
    #[Route('/ajouterTopic', name: 'ajouter_topic')]
    public function ajouterTopic(Request $request): Response
    {
        $topic = new Topic(); // Correct variable name from $top to $topic

        $form = $this->createForm(TopicType::class, $topic); // Pass $topic object to the form

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($topic);
            $entityManager->flush();
            $this->addFlash('success', '');
        }


        return $this->render('topic/ajoutertopic.html.twig', [
            'formulairetopic' => $form->createView(),
        ]);
    }
    #[Route('/UpdateTopic/{Topic_id}', name: 'update_Topic')]
    public function UpdateTopic(Request $request, TopicRepository $repo, $Topic_id, ManagerRegistry $managerRegistry)
    {
        $topic = $repo->find($Topic_id);
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $managerRegistry->getManager();
            $em->flush();
            return $this->redirectToRoute("afficher_post");
        }

        // Ajoutez un bouton de soumission au formulaire
        return $this->render('topic/Updatetopic.html.twig', [
            'formulairetopic' => $form->createView(),
        ]);
    }
    #[Route('/deletetopic/{id}', name: 'delete_topic')]
    function delete(ManagerRegistry $manager, TopicRepository $repo, $id, Request $request)
    {
        $obj = $repo->find($id);
        // Vérifier si l'entité a été trouvée
        $em = $manager->getManager();
        $em->remove($obj);
        $em->flush();
        // Si l'entité n'a pas été trouvée, gérer ce cas (par exemple, afficher un message d'erreur)

    }
}
