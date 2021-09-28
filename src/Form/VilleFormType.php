<?php

namespace App\Form;

use App\Entity\Ville;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class VilleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null, [
                'attr' => [
                    'class' => 'ville_nom'
                ],
            ])
            ->add('codePostal', null, [
                'attr' => [
                    'class' => 'ville_codepostal'
                ],
            ])
            ->add('ville_id', null, [
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'ville_id',
                    'hidden' => true
                ],
                
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
