<?php

namespace App\Controller;

use App\Entity\Livraison;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\LivraisonType;
use App\Repository\LivraisonRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class LivraisonController extends AbstractController
{
    #[Route('/ajouterLivraison', name: 'ajouter_livraison')]
    public function ajouterLivraison(Request $request): Response
    {
        $livraison = new Livraison();
        $user = $this->getUser();

        if ($user) {
            $livraison->setUser($user);
        }

        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($livraison);
            $entityManager->flush();
        }

        return $this->render('livraison/ajouterLivraison.html.twig', [
            'formulaireLivraison' => $form->createView(),
        ]);
    }

    #[Route('/afficherLivraison',name:'afficher_livraison')]
    function affiche(LivraisonRepository $repo){
        $obj=$repo->findAll();
        return $this->render('livraison/afficherLivraison.html.twig',['o'=>$obj]);
    }


    #[Route('/UpdateLivraison/{id}', name: 'update_livraison')]
    public function UpdateLivraison(Request $request, LivraisonRepository $repo, $id, ManagerRegistry $managerRegistry)
    {
    $livraison = $repo->find($id);
    $form = $this->createForm(LivraisonType::class, $livraison);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        $em=$managerRegistry->getManager();
        $em->flush();
        return $this->redirectToRoute("afficher_livraison");
    }

    // Ajoutez un bouton de soumission au formulaire
    $form->add('submit', SubmitType::class, [
        'label' => 'Modifier',
        'attr' => ['class' => 'btn btn-primary']
    ]);

    return $this->render("livraison/updateLivraison.html.twig",["formulaireLivraison"=>$form->createView()]);
}

#[Route('/deleteLivraison/{id}',name:'delete_livraison')]
    function delete(ManagerRegistry $manager , LivraisonRepository $repo , $id){
        $obj = $repo -> find($id);
        $em=$manager->getManager();
        $em->remove($obj);
        $em->flush();

        return $this->redirecttoRoute('afficher_livraison');
    }
}
