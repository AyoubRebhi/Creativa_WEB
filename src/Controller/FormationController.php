<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\InscriptionRepository;
use App\Entity\Inscription;
use Knp\Snappy\Pdf;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/admin/formation')]
class FormationController extends AbstractController
{

    #[Route('/generate-pdf/{id}', name: 'generate_pdf')]
    public function generatePdf(Formation $formation, Pdf $pdf): Response
    {
    // Fetch inscriptions for the given formation
    $inscriptions = $formation->getInscriptions();

    // Render the PDF content using a Twig template
    $html = $this->renderView('pdf/inscriptions.html.twig', [
        'formation' => $formation,
        'inscriptions' => $inscriptions,
    ]);

    // Generate PDF
    $pdfFile = $pdf->getOutputFromHtml($html);

    // Set response headers
    $response = new Response($pdfFile);
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="inscriptions.pdf"');

    return $response;
}
       
    #[Route('/', name: 'app_formation_index', methods: ['GET'])]
    public function index(Request $request, FormationRepository $formationRepository, PaginatorInterface $paginator): Response
    {

        $formations = $formationRepository->findAll();

        $formations = $paginator->paginate(
            $formations, /* query NOT result */
            $request->query->getInt('page', 1),
            2
        );
        return $this->render('formation/index.html.twig', [
            'formations' => $formations,
        ]);
    }

    #[Route('/new', name: 'app_formation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formation/new.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_formation_show', methods: ['GET'])]
    public function show(Formation $formation): Response
    {
        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_formation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Formation $formation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_formation_delete', methods: ['POST'])]
    public function delete(Request $request, Formation $formation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($formation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/admin/formation/{id}/inscriptions', name: 'app_inscriptions_by_formation')]
    public function inscriptionsByFormation(int $id, InscriptionRepository $inscriptionRepository): Response
    {
        // Retrieve the Formation object based on the provided ID
        $formation = $this->getDoctrine()->getRepository(Formation::class)->find($id);

        // If the Formation object does not exist, return a 404 response or handle it accordingly
        if (!$formation) {
            throw $this->createNotFoundException('Formation not found');
        }

        // Get the inscriptions associated with the specified formation
        $inscriptions = $inscriptionRepository->findBy(['formation' => $formation]);

        // Render a template to display the list of inscriptions
        return $this->render('formation/inscriptions_by_formation.html.twig', [
            'formation' => $formation,
            'inscriptions' => $inscriptions,
        ]);
    }


    
}
