<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PostType;
use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }
    #[Route('/afficherPost',name:'afficher_post')]
    function affiche(TopicRepository $repo){
        $obj=$repo->findAll();
        return $this->render('post/afficherpost.html.twig',['o'=>$obj]);
    }
    #[Route('/ajouterPost',name:'ajouter_post')]
    public function ajouterPost(Request $request): Response
    {
        $post = new Post(); 

        $form = $this->createForm(TopicType::class, $topic); 
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post); 
            $entityManager->flush();
            $this->addFlash('success', 'Votre post a été ajoutée avec succès.');}
           
    
        return $this->render('post/ajouterpost.html.twig', [
            'formulairepost' => $form->createView(),
        ]);
    }
    #[Route('/UpdatePost/{id}', name: 'update_Post')]
    public function UpdateTopic(Request $request, PostRepository $repo, $id, ManagerRegistry $managerRegistry)
    {
    $post = $repo->find($id);
    $form = $this->createForm(PostType::class, $post);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        $em=$managerRegistry->getManager();
        $em->flush();
        return $this->redirectToRoute("afficher_post");
    }

 
    $form->add('submit', SubmitType::class, [
        'label' => 'Modifier',
        'attr' => ['class' => 'btn btn-primary']
    ]); 
}
#[Route('/deletepost/{id}', name: 'delete_post')]
function delete(ManagerRegistry $manager, PostRepository $repo, $id, Request $request)
{
    $obj = $repo->find($id);

    if (!$obj) {
        throw $this->createNotFoundException('Le post avec l\'identifiant ' . $id . ' n\'existe pas.');
    }

    // Vérification si le formulaire de confirmation a été soumis
    if ($request->query->get('confirm') === 'true') {
        $em = $manager->getManager();
        $em->remove($obj);
        $em->flush();

        // Redirection vers la route d'affichage des commandes
        return $this->redirectToRoute('afficher_post');
    }
}
}
