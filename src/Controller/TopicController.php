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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\ORM\EntityManagerInterface;


class TopicController extends AbstractController
{
    #[Route('/topic', name: 'app_topic')]
    public function index(): Response
    {
        return $this->render('topic/index.html.twig', [
            'controller_name' => 'TopicController',
        ]);
    }
    #[Route('/affichertopic', name: 'afficher_topic')]
    function affiche(TopicRepository $repo)
    {
        $topics = $repo->findAll();
        return $this->render('topic/affichertopic.html.twig', ['topics' => $topics]);
    }
    #[Route('/ajoutertopic', name: 'ajouter_topic')]
    public function ajouterTopic(Request $request): Response
    {
        $topic = new Topic();

        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form['Image']->getData();
            if ($image) {
                $imageName = uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move($this->getParameter('topic_dir'), $imageName);
                } catch (FileException $e) {
                    return new Response('Failed to upload the image.', Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                $topic->setImage($imageName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($topic);
            $entityManager->flush();

            return $this->redirectToRoute('afficher_topic'); // Redirect to a different route after successful form submission
        }

        return $this->render('topic/ajoutertopic.html.twig', [
            'formulairetopic' => $form->createView(),
        ]);
    }

    #[Route('/Updatetopic/{Topic_id}', name: 'update_Topic')]
    public function UpdateTopic(Request $request, TopicRepository $repo, $Topic_id, EntityManagerInterface $entityManager): Response
    {
        $topic = $repo->find($Topic_id);
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle uploaded image
            $imageFile = $form['Image']->getData();
            if ($imageFile) {
                $fileName = uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('topic_dir'),
                        $fileName
                    );
                } catch (FileException $e) {
                    // Handle file exception
                    return new Response('Failed to upload the image.', Response::HTTP_INTERNAL_SERVER_ERROR);
                }
                // Set the image file name in the entity
                $topic->setImage($fileName);
            }

            // Flush changes to the database
            $entityManager->flush();

            // Add flash message
            $this->addFlash('success', 'Topic updated successfully.');

            // Redirect to the appropriate route, passing any necessary parameters
            return $this->redirectToRoute("afficher_topic");
        }

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
