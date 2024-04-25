<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\Categorie1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/categorie')]
class CategorieController extends AbstractController
{
    #[Route('/test', name: 'app', methods: ['GET'])]
    public function test(): Response
    {
        return $this->render('backOffice.html.twig', []);
    }
    #[Route('/', name: 'app', methods: ['GET'])]
    public function testClient(): Response
    {
        return $this->render('base.html.twig', []);
    }
    #[Route('/admin', name: 'app_categorie_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager
            ->getRepository(Categorie::class)
            ->findAll();

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    #[Route('/client', name: 'app_categorie_indexClient', methods: ['GET'])]
    public function indexClient(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager
            ->getRepository(Categorie::class)
            ->findAll();

        return $this->render('categorie/indexClient.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/new', name: 'app_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(Categorie1Type::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form['image']->getData();

            if ($image) {
                $fileName = uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move($this->getParameter('image_dir'), $fileName); // Move the uploaded file to the configured directory
                } catch (FileException $e) {
                    // Handle file exception
                    // You might want to log the error or show an error message to the user
                    return new Response('Failed to upload the image.', Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                $categorie->setImage($fileName);
            }

            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idCategorie}', name: 'app_categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{idCategorie}/edit', name: 'app_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Categorie1Type::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form['image']->getData();
            if ($image) {
                $imageName = uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move($this->getParameter('image_dir'), $imageName);
                    $categorie->setImage($imageName);
                } catch (FileException $e) {
                    // Handle file exception
                    return new Response('Failed to upload image.', Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }


    #[Route('/{idCategorie}', name: 'app_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $categorie->getIdCategorie(), $request->request->get('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
