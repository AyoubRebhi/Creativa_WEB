<?php

namespace App\Controller;

use App\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommandeController extends AbstractController
{
    #[Route('/ajouterCommande', name: 'ajouter_commande')]
    public function ajouterCommande(Request $request): Response
    {
        $commande = new Commande();
        $user = $this->getUser();

        if ($user) {
            $commande->setUser($user);
        }

        $form = $this->createForm(CommandeType::class, $commande); 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commande);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commande a été ajoutée avec succès.');
        }

        return $this->render('commande/ajouterCommande.html.twig', [
            'formulaireCommande' => $form->createView(),
        ]);
    }

    #[Route('/afficherCommande',name:'afficher_commande')]
    function affiche(CommandeRepository $repo){
        $obj=$repo->findAll();
        return $this->render('commande/afficherCommande.html.twig',['o'=>$obj]);
    }


    #[Route('/UpdateCommande/{id}', name: 'update_Commande')]
    public function UpdateCommande(Request $request, CommandeRepository $repo, $id, ManagerRegistry $managerRegistry)
    {
    $commande = $repo->find($id);
    $form = $this->createForm(CommandeType::class, $commande);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        $em=$managerRegistry->getManager();
        $em->flush();
        return $this->redirectToRoute("afficher_commande");
    }

    // Ajoutez un bouton de soumission au formulaire
    $form->add('submit', SubmitType::class, [
        'label' => 'Modifier',
        'attr' => ['class' => 'btn btn-primary']
    ]);

    return $this->render("commande/updateCommande.html.twig",["formulaireCommande"=>$form->createView()]);
}

#[Route('/deleteCommande/{id}',name:'delete_commande')]
    function delete(ManagerRegistry $manager , CommandeRepository $repo , $id){
        $obj = $repo -> find($id);
        $em=$manager->getManager();
        $em->remove($obj);
        $em->flush();

        return $this->redirecttoRoute('afficher_commande');
    }
}
