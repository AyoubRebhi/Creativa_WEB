<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Projet;
use App\Repository\ProjetRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
class CommandeType extends AbstractType
{
    private $projetRepository;

    public function __construct(ProjetRepository $projetRepository)
    {
        $this->projetRepository = $projetRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idUser')
            ->add('idProjet', EntityType::class, [
                'class' => Projet::class,
                'choice_label' => 'id_projet', // Assurez-vous que 'nom' est le champ à afficher dans la liste des projets
                'placeholder' => 'Sélectionnez un projet',
                'mapped' => false, // Nous ne voulons pas mapper ce champ à une propriété de l'entité Commande
            ])
            ->add('date', DateType::class)
            ->add('dateLivraisonEstimee', DateType::class)
            ->add('codePromo')
            ->add('status')
            ->add('prix')
            ->add('fraisLiv')
            ->add('mtTotal')
            ->add('submit', SubmitType::class);

        // Ajouter un écouteur d'événements pour mettre à jour le prix en fonction du projet sélectionné
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            // Récupérer l'ID du projet sélectionné dans les données du formulaire
            $projetId = $data['idProjet'];

            // Si un projet est sélectionné
            if ($projetId) {
                // Récupérer le projet correspondant depuis la base de données
                $projet = $this->projetRepository->find($projetId);

                // Mettre à jour le champ prix avec le prix du projet
                $form->add('prix', null, [
                    'data' => $projet->getPrix()
                ]);
            } 
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
            // Ajouter cette option pour inclure idProjet dans les données du formulaire
            'include_projet_id' => false,
        ]);
    }
}
