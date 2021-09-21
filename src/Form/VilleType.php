<?php

namespace App\Form;

use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',EntityType::class,[
                'class'=> Ville::class,
                'choice_label'=>'nom',
                'label'=>'Ville: '
            ])
            ->add('codePostal',EntityType::class,[
                'class'=> Ville::class,
                'choice_label'=>'codePostal',
                'label'=>'Code Postal: '
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ville::class,
        ]);
    }
}
