<?php

namespace App\Form;

use App\Entity\Livraison;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idCmd')
            ->add('idUser')
            ->add('status')
            ->add('adresse')
            ->add('fraisLiv')
            ->add('moyenLivraison', ChoiceType::class, [
                'choices' => [
                    'Standards' => 'Standards',
                    'Express' => 'Express',
                ],
                'expanded' => true,
            ])
            ->add('submit',submitType::class);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}
