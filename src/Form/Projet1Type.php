<?php

namespace App\Form;

use App\Entity\Projet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Categorie;
use Symfony\Component\Validator\Constraints\Image;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class Projet1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'constraints' => [
                    new NotBlank(), // Titre should not be blank
                ],
            ])

            ->add('description', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 500, // Description can have a maximum of 255 characters
                    ]),
                ],
            ])
            ->add('media', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image au format JPEG, PNG ou JPG valide.',
                    ]),
                ],
            ])
            ->add('prix', TextType::class, [
                'constraints' => [
                    new NotBlank(), // Prix should not be blank
                ],
            ])
            /*->add('isvisible')
            ->add('createdAt')
            ->add('updatedAt')*/
            ->add('idCategorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'titre',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
