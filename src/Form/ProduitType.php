<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation')
            ->add('prix')
            ->add('quantite')
            ->add('image',FileType::class,['data_class'=>null,'label'=>'Image'])
            ->add('cathegorie')
            ->add('ajouter', SubmitType::class,
                [
                    'attr' => ['class' => 'btn btn-sm btn-primary hvr-ripple-out mb-3'],
                    'label' => 'Ajouter'
                ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
