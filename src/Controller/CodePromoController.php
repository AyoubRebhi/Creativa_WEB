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

        $form = $this->createForm(CodepromoType::class, $codepromo); 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($codepromo);
            $entityManager->flush();

            $this->addFlash('success', 'Votre code promo a été ajoutée avec succès.');
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
        return $this->redirectToRoute("afficher_code$codepromo");
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
}
