<?php

namespace App\Controller;

use App\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\CodepromoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Projet;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\CodePromo;
use App\Controller\CodePromoController; // Importez le contrôleur CodePromoController

class CommandeController extends AbstractController
{
    #[Route('/ajouterCommande', name: 'ajouter_commande')]
public function ajouterCommande(Request $request, CodePromoRepository $codePromoRepository): Response
{
    $commande = new Commande();
    $user = $this->getUser();

    if ($user) {
        $commande->setUser($user);
    }

    $commande->setDate(new \DateTime());
    // Pré-remplit le champ de date estimée avec la date actuelle + 5 jours
    $dateEstimee = new \DateTime();
    $dateEstimee->modify('+5 days');
    $commande->setDateLivraisonEstimee($dateEstimee);
    $commande->setStatus('en cours');
    $commande->setFraisLiv(8);
    
    $form = $this->createForm(CommandeType::class, $commande);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer l'ID du projet à partir des données du formulaire
        $projetId = $form->get('idProjet')->getData();
        
        // Récupérer le code promo saisi par l'utilisateur
        $codePromo = $commande->getCodePromo();
        
        // Rechercher le code promo dans la table "code_promo"
        $codePromoEntity = $codePromoRepository->findOneBy(['codePromo' => $codePromo]);

        // Enregistrez la commande dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commande);
        $entityManager->flush();

        // Redirigez l'utilisateur vers une autre page (par exemple, la page d'affichage des commandes)
        return $this->redirectToRoute('afficher_commande');
    }

    return $this->render('commande/ajouterCommande.html.twig', [
        'formulaireCommande' => $form->createView(),
    ]);
}

#[Route('/ajouterCommandeBack', name: 'ajouter_commande_back')]
public function ajouterCommandeBack(Request $request, CodePromoRepository $codePromoRepository): Response
{
    $commande = new Commande();
    $user = $this->getUser();

    if ($user) {
        $commande->setUser($user);
    }

    $commande->setDate(new \DateTime());
    // Pré-remplit le champ de date estimée avec la date actuelle + 5 jours
    $dateEstimee = new \DateTime();
    $dateEstimee->modify('+5 days');
    $commande->setDateLivraisonEstimee($dateEstimee);
    $commande->setStatus('en cours');
    $commande->setFraisLiv(8);
    
    $form = $this->createForm(CommandeType::class, $commande);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer l'ID du projet à partir des données du formulaire
        $projetId = $form->get('idProjet')->getData();
        
        // Récupérer le code promo saisi par l'utilisateur
        $codePromo = $commande->getCodePromo();
        
        // Rechercher le code promo dans la table "code_promo"
        $codePromoEntity = $codePromoRepository->findOneBy(['codePromo' => $codePromo]);

        // Enregistrez la commande dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commande);
        $entityManager->flush();

        // Redirigez l'utilisateur vers une autre page (par exemple, la page d'affichage des commandes)
        return $this->redirectToRoute('afficher_commande');
    }

    return $this->render('commande/ajouterCommandeBack.html.twig', [
        'formulaireCommande' => $form->createView(),
    ]);
}

    #[Route('/verifierCodePromo', name: 'verifierCodePromo')]
public function verifierCodePromo(Request $request): JsonResponse
{
    // Récupérer le code promo envoyé depuis la requête
    $codePromo = $request->request->get('codePromo'); // Modifier 'code_promo' en 'codePromo'

    // Recherchez le code promo dans la base de données
    $codePromoEntity = $this->getDoctrine()
        ->getRepository(CodePromo::class)
        ->findOneBy(['codePromo' => $codePromo]); // Utiliser 'code_promo' au lieu de 'codePromo'

    // Vérifiez si le code promo existe dans la base de données
    if ($codePromoEntity) {
        // Si le code promo existe, il est considéré comme valide
        $codePromoValide = true;
    } else {
        // Si le code promo n'existe pas, il est considéré comme invalide
        $codePromoValide = false;
    }

    // Retourner une réponse JSON avec le résultat de la vérification
    return new JsonResponse(['valid' => $codePromoValide]);
}
#[Route('/verifierCodePromoBack', name: 'verifierCodePromo_back')]
public function verifierCodePromoBack(Request $request): JsonResponse
{
    // Récupérer le code promo envoyé depuis la requête
    $codePromo = $request->request->get('codePromo'); // Modifier 'code_promo' en 'codePromo'

    // Recherchez le code promo dans la base de données
    $codePromoEntity = $this->getDoctrine()
        ->getRepository(CodePromo::class)
        ->findOneBy(['codePromo' => $codePromo]); // Utiliser 'code_promo' au lieu de 'codePromo'

    // Vérifiez si le code promo existe dans la base de données
    if ($codePromoEntity) {
        // Si le code promo existe, il est considéré comme valide
        $codePromoValide = true;
    } else {
        // Si le code promo n'existe pas, il est considéré comme invalide
        $codePromoValide = false;
    }

    // Retourner une réponse JSON avec le résultat de la vérification
    return new JsonResponse(['valid' => $codePromoValide]);
}



  #[Route('/afficherCommande',name:'afficher_commande')]
    function affiche(CommandeRepository $repo){
        $obj=$repo->findAll();

        return $this->render('commande/afficherCommande.html.twig',['o'=>$obj]);
    }

    #[Route('/afficherCommandeBack',name:'afficher_commande_back')]
    function afficheBack(CommandeRepository $repo){
        $obj=$repo->findAll();

        return $this->render('commande/afficherCommandeBack.html.twig',['o'=>$obj]);
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

#[Route('/UpdateCommandeBack/{id}', name: 'update_Commande_back')]
    public function UpdateCommandeBack(Request $request, CommandeRepository $repo, $id, ManagerRegistry $managerRegistry)
    {
    $commande = $repo->find($id);
    $form = $this->createForm(CommandeType::class, $commande);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        $em=$managerRegistry->getManager();
        $em->flush();
        return $this->redirectToRoute("afficher_commande_back");
    }

    // Ajoutez un bouton de soumission au formulaire
    $form->add('submit', SubmitType::class, [
        'label' => 'Modifier',
        'attr' => ['class' => 'btn btn-primary']
    ]);

    return $this->render("commande/updateCommandeBack.html.twig",["formulaireCommande"=>$form->createView()]);
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

#[Route('/deleteCommandeBack/{id}', name: 'delete_commande_back')]
function deleteBack(ManagerRegistry $manager, CommandeRepository $repo, $id, Request $request)
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
        return $this->redirectToRoute('afficher_commande_back');
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