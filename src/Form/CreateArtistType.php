<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Config\VichUploaderConfig;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CreateArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image de l\'artiste',
                'required' => true,
                'allow_delete' => false,
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
