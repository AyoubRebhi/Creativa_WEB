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
use App\Controller\CodePromoController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use App\Form\LivraisonType;
use App\Entity\Livraison;
use Symfony\Component\Security\Core\Security;
use Twilio\Rest\Client as TwilioClient;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client;

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
        return $this->redirectToRoute('afficher_commande_back');
    }

    return $this->render('commande/ajouterCommandeBack.html.twig', [
        'formulaireCommande' => $form->createView(),
    ]);
}

    #[Route('/verifierCodePromo', name: 'verifierCodePromo')]
public function verifierCodePromo(Request $request): JsonResponse
{
    // Récupérer le code promo envoyé depuis la requête
    $codePromo = $request->request->get('codePromo'); 

    // Recherchez le code promo dans la base de données
    $codePromoEntity = $this->getDoctrine()
        ->getRepository(CodePromo::class)
        ->findOneBy(['codePromo' => $codePromo]);
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
    $codePromo = $request->request->get('codePromo'); 

    // Recherchez le code promo dans la base de données
    $codePromoEntity = $this->getDoctrine()
        ->getRepository(CodePromo::class)
        ->findOneBy(['codePromo' => $codePromo]); 

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



#[Route('/afficherCommande', name: 'afficher_commande')]
function afficheCommande(Request $request, CommandeRepository $repo){
    $currentPage = $request->query->getInt('page', 1);
    $limit = 10; // Number of items per page
    $offset = ($currentPage - 1) * $limit;
    
    $obj = $repo->findBy([], [], $limit, $offset); // Fetch paginated data
    
    $totalItems = $repo->count([]); // Total number of items
    $totalPages = ceil($totalItems / $limit); // Calculate total pages

    return $this->render('commande/afficherCommande.html.twig', [
        'o' => $obj,
        'currentPage' => $currentPage,
        'totalPages' => $totalPages
    ]);
}

#[Route('/afficherCommandeBack', name: 'afficher_commande_back')]
function afficheCommandeBack(Request $request, CommandeRepository $repo){
    $currentPage = $request->query->getInt('page', 1);
    $limit = 10; // Number of items per page
    $offset = ($currentPage - 1) * $limit;
    
    $obj = $repo->findBy([], [], $limit, $offset); // Fetch paginated data
    
    $totalItems = $repo->count([]); // Total number of items
    $totalPages = ceil($totalItems / $limit); // Calculate total pages

    return $this->render('commande/afficherCommandeBack.html.twig', [
        'o' => $obj,
        'currentPage' => $currentPage,
        'totalPages' => $totalPages
    ]);
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


#[Route('/cancelCommande/{id}', name: 'cancel_commande')]
function cancel(ManagerRegistry $manager, CommandeRepository $repo, $id, Request $request)
{
    $commande = $repo->find($id);

    if (!$commande) {
        throw $this->createNotFoundException('La commande avec l\'identifiant ' . $id . ' n\'existe pas.');
    }

    // Vérification si le formulaire de confirmation a été soumis
    if ($request->query->get('confirm') === 'true') {
        // Mettre à jour le statut de la commande
        $commande->setStatus(Commande::STATUS_ANNULE);

        $em = $manager->getManager();
        $em->persist($commande);
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
                <h2>Confirmation d\'annulation</h2>
                <p>Voulez-vous vraiment annuler cette commande ?</p>
                <a class="confirm-button" href="' . $this->generateUrl('cancel_commande', ['id' => $id, 'confirm' => 'true']) . '">Confirmer</a>
                <a class="cancel-button" href="' . $this->generateUrl('afficher_commande') . '">Annuler</a>
            </div>
        </body>
    </html>
    ');
}

#[Route('/cancelCommandeBack/{id}', name: 'cancel_commande_back')]
function cancelBack(ManagerRegistry $manager, CommandeRepository $repo, $id, Request $request)
{
    $commande = $repo->find($id);

    if (!$commande) {
        throw $this->createNotFoundException('La commande avec l\'identifiant ' . $id . ' n\'existe pas.');
    }

    // Vérification si le formulaire de confirmation a été soumis
    if ($request->query->get('confirm') === 'true') {
        // Mettre à jour le statut de la commande
        $commande->setStatus(Commande::STATUS_ANNULE);

        $em = $manager->getManager();
        $em->persist($commande);
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
                <h2>Confirmation d\'annulation</h2>
                <p>Voulez-vous vraiment annuler cette commande ?</p>
                <a class="confirm-button" href="' . $this->generateUrl('cancel_commande_back', ['id' => $id, 'confirm' => 'true']) . '">Confirmer</a>
                <a class="cancel-button" href="' . $this->generateUrl('afficher_commande_back') . '">Annuler</a>
            </div>
        </body>
    </html>
    ');
}


/*private function envoyerSms($phoneNumber, $message, $twilioAccountSid, $twilioAuthToken, $twilioPhoneNumber)
{
    $sid = $twilioAccountSid;
    $token = $twilioAuthToken;
    $twilioPhoneNumber = $twilioPhoneNumber;

    $cleanedPhoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
    $cleanedPhoneNumber = '+216' . $cleanedPhoneNumber;

    $twilio = new Client($sid, $token);
    $twilio->messages->create(
        $cleanedPhoneNumber,
        ['from' => $twilioPhoneNumber, 'body' => $message]
    );
}*/




#[Route('/passerLivraison/{id}', name: 'passer_livraison')]
public function passerLivraison(Request $request, CommandeRepository $commandeRepository, $id): Response
{
    // Récupérer la commande
    $commande = $commandeRepository->find($id);

    // Créer le formulaire de livraison en utilisant LivraisonType
    $livraison = new Livraison();
    $livraison->setStatus('en cours');
    $livraison->setIdCmd($id); // Pré-remplir l'ID de la commande dans le formulaire
    $form = $this->createForm(LivraisonType::class, $livraison);

    // Ajouter un bouton de soumission au formulaire
    $form->add('submit', SubmitType::class, [
        'label' => 'submit',
        'attr' => ['class' => 'btn btn-primary'],
    ]);

    // Traiter la soumission du formulaire
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrer la livraison dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($livraison);
        $entityManager->flush();

            /*$twilioAccountSid = $_ENV['twilio_account_sid'];
            $twilioAuthToken = $_ENV['twilio_auth_token'];
            $twilioPhoneNumber = $_ENV['twilio_phone_number'];
            $myPhoneNumber = "53125536";
            
            
            // Envoyer un SMS à votre numéro personnel
            $this->envoyerSms($myPhoneNumber,"Nous sommes heureux de vous informer que votre commande est actuellement en cours de traitement. Notre équipe s'affaire à préparer vos articles avec le plus grand soin afin de vous garantir une satisfaction totale.",$twilioAccountSid,$twilioAuthToken,$twilioPhoneNumber);
    
       */

        return $this->redirectToRoute('afficher_livraison');
    }

    // Afficher la vue du formulaire de livraison
    return $this->render('livraison/ajouterLivraison.html.twig', [
        'formulaireLivraison' => $form->createView(),
        'commande' => $commande,
    ]);
}

}