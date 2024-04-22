<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PostType;
use App\Entity\Post;
use App\Repository\TopicRepository;
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
    #[Route('/afficherPost/{Topic_id}',name:'afficher_post')]
    function affiche(PostRepository $repo,int $Topic_id){
        $posts = $repo->findBy(['topicId' =>$Topic_id]);
        return $this->render('post/afficherpost.html.twig',['o'=>$posts,'topicid'=>$Topic_id]);
    }
    #[Route('/lire',name:'lire')]
    public function showMedia(): Response
    {
        $filePath = $this->getParameter('kernel.project_dir') . 'public\media\test1.mp4' ;

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('The media file does not exist.');
        }

        return new BinaryFileResponse($filePath);
    ;
    }
    #[Route('/ajouterPost',name:'ajouter_post')]
    public function ajouterPost(Request $request): Response
    {
        $post = new Post(); 

        $form = $this->createForm(PostType::class); 
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            dump($formData); 
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

 
    return $this->render('post/updatepost.html.twig', [
        'formulairepost' => $form->createView(),
    ]);

}
#[Route('/deletepost/{id}', name: 'delete_post')]
function delete(ManagerRegistry $manager, PostRepository $repo, $id, Request $request)
{
    $obj = $repo->find($id);
 $em = $manager->getManager();
 $em->remove($obj);
$em->flush();
return new Response('Le formulaire de confirmation n\'a pas été soumis.');
}
#[Route('/affpost/{post_id}', name: 'lire_post')]
function lire(PostRepository $repo,$post_id)
{
    $post = $repo->find($post_id);
    return $this->render('post/afficher.html.twig',['o'=>$post]);
}
}
