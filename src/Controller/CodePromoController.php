<?php

namespace App\Controller;

use App\Entity\CodePromo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CodepromoType;
use App\Repository\CodepromoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
class CodePromoController extends AbstractController

{
    #[Route('/ajouterCodepromo', name: 'ajouter_codepromo')]
    public function ajouterCodepromo(Request $request): Response
    {
        $codepromo = new Codepromo();
        $user = $this->getUser();

        if ($user) {
            $codepromo->setUser($user);
        }

        // Définir la date actuelle comme valeur par défaut pour le champ 'date'
        $codepromo->setDate(new \DateTime());

        // Ajouter deux jours à la date actuelle pour la date d'expiration
        $dateExpiration = new \DateTime();
        $dateExpiration->modify('+2 days');
        $codepromo->setDateExpiration($dateExpiration);

        $form = $this->createForm(CodepromoType::class, $codepromo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($codepromo);
            $entityManager->flush();

            $this->addFlash('success', 'Votre code promo a été ajouté avec succès.');

            return $this->redirectToRoute('afficher_codepromo');

        }

        return $this->render('code_promo/ajouterCodepromo.html.twig', [
            'formulaireCodepromo' => $form->createView(),
        ]);
    }

    #[Route('/afficherCodepromo',name:'afficher_codepromo')]
    function affiche(CodepromoRepository $repo){
        $obj=$repo->findAll();
        return $this->render('code_promo/afficherCodepromo.html.twig',['o'=>$obj]);
    }


    #[Route('/UpdateCodepromo/{id}', name: 'update_Codepromo')]
    public function UpdateCodepromo(Request $request, CodepromoRepository $repo, $id, ManagerRegistry $managerRegistry)
    {
    $codepromo = $repo->find($id);
    $form = $this->createForm(CodepromoType::class, $codepromo);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        $em=$managerRegistry->getManager();
        $em->flush();
        return $this->redirectToRoute("afficher_codepromo");
    }

    // Ajoutez un bouton de soumission au formulaire
    $form->add('submit', SubmitType::class, [
        'label' => 'Modifier',
        'attr' => ['class' => 'btn btn-primary']
    ]);

    return $this->render("code_promo/updateCodepromo.html.twig",["formulaireCodepromo"=>$form->createView()]);
}

#[Route('/deleteCodepromo/{id}',name:'delete_codepromo')]
    function delete(ManagerRegistry $manager , CodepromoRepository $repo , $id){
        $obj = $repo -> find($id);
        $em=$manager->getManager();
        $em->remove($obj);
        $em->flush();

        return $this->redirecttoRoute('afficher_codepromo');
    }

    #[Route('/verifierCodePromo', name: 'verifierCodePromo')]
public function verifierCodePromo(Request $request)
{
    // Récupérer le code promo envoyé depuis la requête
    $codePromo = $request->request->get('code_promo');

    // Recherchez le code promo dans la base de données
    $codePromoEntity = $this->getDoctrine()
        ->getRepository(CodePromo::class)
        ->findOneBy(['code' => $codePromo]);

    // Vérifiez si le code promo existe dans la base de données
    if ($codePromoEntity) {
        // Si oui, renvoyer le pourcentage de réduction associé au code promo
        return new JsonResponse(['pourcentage' => $codePromoEntity->getPourcentage()]);
    } else {
        // Si le code promo n'existe pas dans la base de données, renvoyez une réponse indiquant que le code promo est invalide
        return new JsonResponse(['erreur' => 'Code promo invalide'], Response::HTTP_BAD_REQUEST);
    }
}

}
