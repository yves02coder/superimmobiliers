<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\Immobilier;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImmobilierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('rooms')
            ->add('bedrooms')
            ->add('surface')
            ->add('price')
            ->add('places')
            ->add('floor')
            ->add('garage')
            ->add('image', FileType::class,[
                'label'=>false,
                'multiple'=> true,
                'mapped'=>false,
                'required'=> false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Immobilier::class,
        ]);
    }
}
