<?php

namespace App\Form;

use App\Entity\Cmnt;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',TextType::class,[

                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('nickname',TextType::class,[

                'attr'=>[
                    'class'=>'form-control'
                ]])
            ->add('cnt',CKEditorType::class,[

                'attr'=>[
                    'class'=>'form-control'
                ]])
            ->add('rgpd',CheckboxType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cmnt::class,
        ]);
    }
}
