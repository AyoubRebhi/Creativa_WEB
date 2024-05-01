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
        $projects = $this->projetRepository->findAll();
        $projectChoices = [];

        foreach ($projects as $project) {
            $projectChoices[$project->getIdProjet()] = $project->getIdProjet();
        }

        $builder
            ->add('idUser')
            ->add('idProjet', ChoiceType::class, [
                'choices' => $projectChoices,
                'placeholder' => 'Sélectionnez un projet',
            ])
            ->add('date', DateType::class)
            ->add('dateLivraisonEstimee', DateType::class)
            ->add('codePromo')
            ->add('status', null, [
                'disabled' => true, //read only
            ])
            ->add('prix')
            ->add('fraisLiv', null, [
                'disabled' => true, //read only
            ])
            ->add('mtTotal')
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn btn-primary',
                    'style' => 'font-size: 20px; background-color: #E9BB79; color: #ffffff; padding: 15px 20px; border: none; border-radius: 4px; cursor: pointer; margin-top: 20px;' // Ajoutez des styles CSS directement au bouton
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
