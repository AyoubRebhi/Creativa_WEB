<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class Categorie1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('image', FileType::class, [
                'required' => false, // Allow the field to be empty
                'mapped' => false, // Tell Symfony not to map this field to any property on your entity
                'constraints' => [
                    new Image([ // Add constraints for image file
                        'maxSize' => '5M', // Max size of the file
                        'mimeTypes' => [ // Allowed MIME types
                            'image/jpeg',
                            'image/png',
                            // Add more if needed
                        ],
                    ]),
                ],
            ])
            ->add('description');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
