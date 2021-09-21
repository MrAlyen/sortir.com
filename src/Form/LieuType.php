<?php

namespace App\Form;

use App\Entity\Lieu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $lieu = new Lieu();

        $builder
            ->add('nom',EntityType::class,[
                'class'=> Lieu::class,
                'choice_label'=>'nom',
                'label'=>'Lieu : '
            ])
            ->add('rue',EntityType::class,[
                'class'=> Lieu::class,
                'choice_label'=>'rue ',
                'label'=>'Rue : '
            ])
            ->add('latitude',EntityType::class,[
                'class'=> Lieu::class,
                'choice_label'=>'latitude ',
                'label'=>'Latitude : '
            ])
            ->add('longitude',EntityType::class,[
                'class'=> Lieu::class,
                'choice_label'=>'longitude ',
                'label'=>'Longitude : '
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
