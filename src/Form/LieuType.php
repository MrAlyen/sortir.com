<?php

namespace App\Form;

use App\Entity\Lieu;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('rue', ChoiceType::class,[
                'placeholder' => 'rue (choisir un lieu)',
                'required'=>false
            ])
            /*->add('rue',EntityType::class,[
                'class'=> Lieu::class,
                'query_builder'=> function(EntityRepository $entityRepository){
                    return $entityRepository->createQueryBuilder('l')
                        ->andWhere('l.nom');
                },
                'choice_label'=>'rue',
                'label'=>'Rue : '
            ])
            ->add('latitude',EntityType::class,[
                'class'=> Lieu::class,
                'query_builder'=> function(EntityRepository $entityRepository){
                    return $entityRepository->createQueryBuilder('l')
                        ->andWhere('l.nom');
                },
                'choice_label'=>'latitude',
                'label'=>'Latitude : '
            ])
            ->add('longitude',EntityType::class,[
                'class'=> Lieu::class,
                'query_builder'=> function(EntityRepository $entityRepository){
                    return $entityRepository->createQueryBuilder('l')
                        ->andWhere('l.nom');
                },
                'choice_label'=>'longitude',
                'label'=>'Longitude : '
            ])*/
           ;


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
