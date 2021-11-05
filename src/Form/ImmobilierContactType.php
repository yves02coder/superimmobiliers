<?php

namespace App\Form;



use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImmobilierContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,[
              'disabled'=>true,
              'attr'=>[
                  'class'=>'form-control'
              ]
    ])

            ->add('email',EmailType::class,[
                'label'=>'votre e-mail',
                'attr'=>[
        'class'=>'form-control'
    ]
            ])
            ->add('message',CKEditorType::class,[
                'label'=>'votre message'
            ])
            ->getForm();
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
