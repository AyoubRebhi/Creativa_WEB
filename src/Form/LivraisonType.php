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
            ->add('status',null,[
                'disabled' => true, //read only
            ])            
            ->add('adresse')
            ->add('fraisLiv', null, [
                'label' => 'Frais de livraison',
                'required' => false, 
                
            ])
            
            ->add('moyenLivraison', ChoiceType::class, [
                'choices' => [
                    'Standards' => 'Standards',
                    'Express' => 'Express',
                ],
                'expanded' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn btn-primary',
                    'style' => 'font-size: 20px; background-color: #E9BB79; color: #ffffff; padding: 15px 20px; border: none; border-radius: 4px; cursor: pointer; margin-top: 20px;' // Ajoutez des styles CSS directement au bouton
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}
