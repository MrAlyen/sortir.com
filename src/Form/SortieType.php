<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            /*->add('Campus', EntityType::createChoiceLabel(), [
                'label' => 'Campus: '
            ])
            ->add('Ville', EntityType::createChoiceLabel(), [
                'label' => 'Ville: '
            ])
            ->add('lieu', ChoiceType::class, [
                'label' => 'Lieu: '
            ])
            ->add('Rue', ChoiceType::class, [
                'label' => 'Rue: '
            ])
            ->add('code_postal', ChoiceType::class, [
                'label' => 'Code postal: '
            ])
            ->add('latitude', ChoiceType::class, [
                'label' => 'latitude: '
            ])
            ->add('longitude', ChoiceType::class, [
                'label' => 'longitude: '
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
