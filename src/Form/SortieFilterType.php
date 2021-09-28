<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class,[
                'class'=>Campus::class,
                'choice_label'=>'nom',
                'label'=>'Campus : ',
                'mapped' => false,
                'required' => false
            ])
            ->add('boutNom',SearchType::class,[
                'label'=>'Le nom de la sortie contient : ',
                'mapped' => false,
                'required' => false
            ])
            ->add('dateDebut', DateTimeType::class,[
                'label' => 'Entre  ',
                'html5' => 'true',
                'widget' => 'single_text',
                'mapped' => false,
                'required' => false
            ])
            ->add('dateFin', DateTimeType::class,[
                'label' => 'et  ',
                'html5' => 'true',
                'widget' => 'single_text',
                'mapped' => false,
                'required' => false
            ])
            ->add('organisateur',CheckboxType::class,[
                'label' => 'Sortie dont je suis l\'organisatrice/teur',
                'mapped' => false,
                'required' => false,
                
            ])
            ->add('inscrit',CheckboxType::class,[
                'label' => 'Sortie auxquelles je suis inscrit/e',
                'mapped' => false,
                'required' => false
            ])
            ->add('pasInscrit',CheckboxType::class,[
                'label' => 'Sortie auxquelles je ne suis pas  inscrit/e',
                'mapped' => false,
                'required' => false
            ])
            ->add('passee',CheckboxType::class,[
                'label' => 'Sortie passées',
                'mapped' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
