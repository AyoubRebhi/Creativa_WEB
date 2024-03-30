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

#[Route('/deleteLivraison/{id}', name: 'delete_livraison')]
function deleteLivraison(ManagerRegistry $manager, LivraisonRepository $repo, $id)
{
    $livraison = $repo->find($id);

    // Vérification si le formulaire de confirmation a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
        $em = $manager->getManager();
        $em->remove($livraison);
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

                    .confirmation-container button {
                        display: inline-block;
                        padding: 10px 20px;
                        margin-right: 10px;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
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
                    <h2>Confirmation de suppression</h2>
                    <p>Voulez-vous vraiment supprimer cette livraison ?</p>
                    <form method="post">
                        <button class="confirm-button" type="submit" name="confirm_delete">Confirmer</button>
                        <a class="cancel-button" href="' . $this->generateUrl('afficher_livraison') . '">Annuler</a>
                    </form>
                </div>
            </body>
        </html>
    ');
}
}
