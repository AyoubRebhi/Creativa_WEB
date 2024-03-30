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

#[Route('/deleteCommande/{id}', name: 'delete_commande')]
function delete(ManagerRegistry $manager, CommandeRepository $repo, $id, Request $request)
{
    $obj = $repo->find($id);

    if (!$obj) {
        throw $this->createNotFoundException('La commande avec l\'identifiant ' . $id . ' n\'existe pas.');
    }

    // Vérification si le formulaire de confirmation a été soumis
    if ($request->query->get('confirm') === 'true') {
        $em = $manager->getManager();
        $em->remove($obj);
        $em->flush();

        // Redirection vers la route d'affichage des commandes
        return $this->redirectToRoute('afficher_commande');
    }

    // Affichage de la page de confirmation
return new Response('
<html>
    <head>
        <style>
            .confirmation-container {
                width: 400px;
                margin: 20px auto;
                padding: 20px;
                border: 2px solid #007bff;
                border-radius: 5px;
                background-color: #f8f9fa;
                text-align: center;
            }

            .confirmation-container h2 {
                color: #007bff;
            }

            .confirmation-container p {
                margin-bottom: 20px;
            }

            .confirmation-container a {
                display: inline-block;
                text-decoration: none;
                padding: 10px 20px;
                margin-right: 10px;
                border-radius: 5px;
                color: #fff;
            }

            .confirm-button {
                background-color: #007bff;
            }

            .cancel-button {
                background-color: #dc3545;
            }
        </style>
    </head>
    <body>
        <div class="confirmation-container">
            <h2>Confirmation de suppression</h2>
            <p>Voulez-vous vraiment supprimer cette commande ?</p>
            <a class="confirm-button" href="' . $this->generateUrl('delete_commande', ['id' => $id, 'confirm' => 'true']) . '">Confirmer</a>
            <a class="cancel-button" href="' . $this->generateUrl('afficher_commande') . '">Annuler</a>
        </div>
    </body>
</html>
');

}
}