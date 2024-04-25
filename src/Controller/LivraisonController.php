<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\LivraisonType;
use App\Repository\LivraisonRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Twilio\Rest\Client as TwilioClient;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client;

class LivraisonController extends AbstractController
{
    private function envoyerSms($phoneNumber, $message, $twilioAccountSid, $twilioAuthToken, $twilioPhoneNumber)
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
    }
    
    

/**
     * @Route("/ajouterLivraison", name="ajouter_livraison")
     */
        public function ajouterLivraison(Request $request): Response
    {
        // Créer une nouvelle instance de Livraison
        $livraison = new Livraison();
        $user = $this->getUser();
    
        if ($user) {
            $livraison->setUser($user);
        }
    
        $livraison->setStatus('En cours');
    
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le frais de livraison à partir du formulaire
            $fraisLiv = $form->get('fraisLiv')->getData();
            $livraison->setFraisLiv($fraisLiv);
            
            $idCmd = $form->get('idCmd')->getData();
            
            // Associer la commande à la livraison
            $commande = $this->getDoctrine()->getRepository(Commande::class)->find($idCmd);
            $livraison->setCommande($commande);
    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($livraison);
            $entityManager->flush();
    
            

            $twilioAccountSid = $_ENV['twilio_account_sid'];
            $twilioAuthToken = $_ENV['twilio_auth_token'];
            $twilioPhoneNumber = $_ENV['twilio_phone_number'];
            $myPhoneNumber = "44812849";
            
            
            // Envoyer un SMS à votre numéro personnel
            $this->envoyerSms($myPhoneNumber,"Nous sommes heureux de vous informer que votre commande est actuellement en cours de traitement. Notre équipe s'affaire à préparer vos articles avec le plus grand soin afin de vous garantir une satisfaction totale.",$twilioAccountSid,$twilioAuthToken,$twilioPhoneNumber);
    

            return $this->redirectToRoute('afficher_livraison');
        }
    
        return $this->render('livraison/ajouterLivraison.html.twig', [
            'formulaireLivraison' => $form->createView()
        ]);
    }
    





 /**
     * @Route("/ajouterLivraisonBack", name="ajouter_livraison_Back")
     */
    public function ajouterLivraisonBack(Request $request): Response
{
    $livraison = new Livraison();
    $user = $this->getUser();

    if ($user) {
        $livraison->setUser($user);
    }

    $livraison->setStatus('en cours');

    $form = $this->createForm(LivraisonType::class, $livraison);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer le frais de livraison à partir du formulaire
        $fraisLiv = $form->get('fraisLiv')->getData();
        $livraison->setFraisLiv($fraisLiv);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($livraison);
        $entityManager->flush();
        return $this->redirectToRoute('afficher_livraison_Back');

    }

    return $this->render('livraison/ajouterLivraisonBack.html.twig', [
        'formulaireLivraison' => $form->createView(),
    ]);
}

    
    

/**
     * @Route("/afficherLivraison", name="afficher_livraison")
     */
    function affiche(Request $request, LivraisonRepository $repo){
    $currentPage = $request->query->getInt('page', 1);
    $limit = 10; // Number of items per page
    $offset = ($currentPage - 1) * $limit;
    
    $obj = $repo->findBy([], [], $limit, $offset); // Fetch paginated data
    
    $totalItems = $repo->count([]); // Total number of items
    $totalPages = ceil($totalItems / $limit); // Calculate total pages

    return $this->render('livraison/afficherLivraison.html.twig', [
        'o' => $obj,
        'currentPage' => $currentPage,
        'totalPages' => $totalPages
    ]);
}

 /**
     * @Route("/afficherLivraisonBack", name="afficher_livraison_Back")
     */
    function afficheLivraisonBack(Request $request, LivraisonRepository $repo){
    $currentPage = $request->query->getInt('page', 1);
    $limit = 10; // Number of items per page
    $offset = ($currentPage - 1) * $limit;
    
    $obj = $repo->findBy([], [], $limit, $offset); // Fetch paginated data
    
    $totalItems = $repo->count([]); // Total number of items
    $totalPages = ceil($totalItems / $limit); // Calculate total pages

    return $this->render('livraison/afficherLivraisonBack.html.twig', [
        'o' => $obj,
        'currentPage' => $currentPage,
        'totalPages' => $totalPages
    ]);
}



/**
     * @Route("/UpdateLivraison/{id}", name="update_livraison")
     */
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

/**
     * @Route("/UpdateLivraisonBack/{id}", name="update_livraison_Back")
     */    
    public function UpdateLivraisonBack(Request $request, LivraisonRepository $repo, $id, ManagerRegistry $managerRegistry)

    {
    $livraison = $repo->find($id);
    $form = $this->createForm(LivraisonType::class, $livraison);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        $em=$managerRegistry->getManager();
        $em->flush();
        return $this->redirectToRoute("afficher_livraison_Back");
    }

    // Ajoutez un bouton de soumission au formulaire
    $form->add('submit', SubmitType::class, [
        'label' => 'Modifier',
        'attr' => ['class' => 'btn btn-primary']
    ]);

    return $this->render("livraison/updateLivraisonBack.html.twig",["formulaireLivraison"=>$form->createView()]);
}



/**
     * @Route("/cancelLivraison/{id}", name="cancel_livraison")
     */
    function cancel(ManagerRegistry $manager, LivraisonRepository $repo, $id, Request $request)
{
    $livraison = $repo->find($id);

    if (!$livraison) {
        throw $this->createNotFoundException('La livraison avec l\'identifiant ' . $id . ' n\'existe pas.');
    }

    // Vérification si le formulaire de confirmation a été soumis
    if ($request->query->get('confirm') === 'true') {
        // Mettre à jour le statut de la livraison
        $livraison->setStatus(Livraison::STATUS_ANNULE);

        $em = $manager->getManager();
        $em->persist($livraison);
        $em->flush();

        // Redirection vers la route d'affichage des livraisons
        return $this->redirectToRoute('afficher_livraison');
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

                    .confirmation-container a, .confirmation-container button {
                        display: inline-block;
                        padding: 10px 20px;
                        margin-right: 10px;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        text-decoration: none;
                    }

                    .confirm-button {
                        background-color: #007bff;
                        color: #fff;
                    }

                    .cancel-button {
                        background-color: #dc3545;
                        color: #fff;
                    }
                </style>
            </head>
            <body>
                <div class="confirmation-container">
                    <h2>Confirmation d\'annulation</h2>
                    <p>Voulez-vous vraiment annuler cette livraison ?</p>
                    <a class="confirm-button" href="' . $this->generateUrl('cancel_livraison', ['id' => $id, 'confirm' => 'true']) . '">Confirmer</a>
                    <a class="cancel-button" href="' . $this->generateUrl('afficher_livraison') . '">Annuler</a>
                </div>
            </body>
        </html>
    ');
}


/**
     * @Route("/cancelLivraisonBack/{id}", name="cancel_livraison_back")
     */
    function cancelBack(ManagerRegistry $manager, LivraisonRepository $repo, $id, Request $request)
{
    $livraison = $repo->find($id);

    if (!$livraison) {
        throw $this->createNotFoundException('La livraison avec l\'identifiant ' . $id . ' n\'existe pas.');
    }

    // Vérification si le formulaire de confirmation a été soumis
    if ($request->query->get('confirm') === 'true') {
        // Mettre à jour le statut de la livraison
        $livraison->setStatus(Livraison::STATUS_ANNULE);

        $em = $manager->getManager();
        $em->persist($livraison);
        $em->flush();

        // Redirection vers la route d'affichage des livraisons
        return $this->redirectToRoute('afficher_livraison_Back');
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

                    .confirmation-container a, .confirmation-container button {
                        display: inline-block;
                        padding: 10px 20px;
                        margin-right: 10px;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        text-decoration: none;
                    }

                    .confirm-button {
                        background-color: #007bff;
                        color: #fff;
                    }

                    .cancel-button {
                        background-color: #dc3545;
                        color: #fff;
                    }
                </style>
            </head>
            <body>
                <div class="confirmation-container">
                    <h2>Confirmation d\'annulation</h2>
                    <p>Voulez-vous vraiment annuler cette livraison ?</p>
                    <a class="confirm-button" href="' . $this->generateUrl('cancel_livraison_back', ['id' => $id, 'confirm' => 'true']) . '">Confirmer</a>
                    <a class="cancel-button" href="' . $this->generateUrl('afficher_livraison_Back') . '">Annuler</a>
                </div>
            </body>
        </html>
    ');
}

/**
     * @Route("/afficher-carte", name="afficher_carte_livraison")
     */
    public function afficherCarte(): Response
    {
        // Redirection vers la route d'affichage des livraisons
        return $this->render('livraison/map.html.twig');

    }
   
}