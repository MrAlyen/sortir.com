<?php

namespace App\Form;


use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieModifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $lieu = new Lieu();
        $rue = $lieu->getRue();
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie: '
            ])
             ->add('dateHeureDebut', DateTimeType::class, [
                 'label' => 'Date et heure de la sortie: ',
                 'html5' => 'true',
                 'widget' => 'single_text',
             ])
             ->add('dateLimiteInscription', DateTimeType::class, [
                 'label' => 'Date limite d\'inscription: ',
                 'html5' => 'true',
                 'widget' => 'single_text',
             ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'label' => 'Nombre de place: '
            ])
            ->add('Duree', IntegerType::class, [
                'label' => 'Durée : '
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description et infos: '

            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'nom',
                'label' => 'Ville : ',
                'mapped' => false,
                'required' => true
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'label' => 'Lieu : ',
                'required' => true
            ])
            ->add('code_postal', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'codePostal',
                'label' => 'Code postal : ',
                'mapped' => false,
                'required' => true
            ])
            ->add('rue', EntityType::class,[
                'class' => Lieu::class,
                'choice_label' => 'rue',
                'label' => 'Rue : ',
                'mapped' => false,
                'required'=> true
            ])
           ->add('latitude', EntityType::class,[
               'class' => Lieu::class,
               'choice_label' => 'latitude',
               'label' => 'latitude : ',
               'mapped' => false,
               'required'=> true
           ])
           ->add('longitude', EntityType::class,[
               'class' => Lieu::class,
               'choice_label' => 'longitude',
               'label' => 'Longitude : ',
               'mapped' => false,
               'required'=> true
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
