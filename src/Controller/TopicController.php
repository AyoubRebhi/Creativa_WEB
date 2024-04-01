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

class TopicController extends AbstractController
{
    #[Route('/topic', name: 'app_topic')]
    public function index(): Response
    {
        return $this->render('topic/index.html.twig', [
            'controller_name' => 'TopicController',
        ]);
    }
    #[Route('/afficherTopic',name:'afficher_topic')]
    function affiche(TopicRepository $repo){
        $obj=$repo->findAll();
        return $this->render('topic/affichertopic.html.twig',['o'=>$obj]);
    }
    #[Route('/ajouterTopic',name:'ajouter_topic')]
    public function ajouterTopic(Request $request): Response
    {
        $topic = new Topic(); // Correct variable name from $top to $topic

        $form = $this->createForm(TopicType::class, $topic); // Pass $topic object to the form
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($topic); 
            $entityManager->flush();
            $this->addFlash('success', 'Votre commande a été ajoutée avec succès.');}
           
    
        return $this->render('topic/ajoutertopic.html.twig', [
            'formulairetopic' => $form->createView(),
        ]);
    }
     #[Route('/UpdateTopic/{id}', name: 'update_Topic')]
    public function UpdateTopic(Request $request, TopicRepository $repo, $id, ManagerRegistry $managerRegistry)
    {
    $topic = $repo->find($id);
    $form = $this->createForm(TopicType::class, $topic);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        $em=$managerRegistry->getManager();
        $em->flush();
        return $this->redirectToRoute("afficher_post");
    }

    // Ajoutez un bouton de soumission au formulaire
    $form->add('submit', SubmitType::class, [
        'label' => 'Modifier',
        'attr' => ['class' => 'btn btn-primary']
    ]);

    
}
#[Route('/deletepost/{id}', name: 'delete_post')]
function delete(ManagerRegistry $manager, TopicRepository $repo, $id, Request $request)
{
    $obj = $repo->find($id);

    if (!$obj) {
        throw $this->createNotFoundException('Le topic avec l\'identifiant ' . $id . ' n\'existe pas.');
    }

    // Vérification si le formulaire de confirmation a été soumis
    if ($request->query->get('confirm') === 'true') {
        $em = $manager->getManager();
        $em->remove($obj);
        $em->flush();

        // Redirection vers la route d'affichage des commandes
        return $this->redirectToRoute('afficher_topic');
    }
}
}
