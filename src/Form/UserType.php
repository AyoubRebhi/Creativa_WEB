<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName')
            ->add('firstName')
            ->add('username')
            ->add('password')
            // ->add('role')
            // ->add('biography')
            ->add('address')
            // ->add('profileImagePath')
            ->add('email')
            // ->add('imgpath')
            ->add('numtel')
            // ->add('blocked')
            // ->add('blockEndDate')
            // ->add('idProjet')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
