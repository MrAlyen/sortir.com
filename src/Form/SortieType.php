<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
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
                'placeholder' => 'Ville',
                'label' => 'Ville : ',
                'mapped' => false,
                'required' => false
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'placeholder' => 'lieu (choisir une ville)',
                'choice_label' => 'nom',
                'label' => 'Lieu : ',
                'required' => false
            ])
            ->add('code_postal', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'codePostal',
                'placeholder' => 'code postal (choisir un lieu)',
                'label' => 'Code postal : ',
                'mapped' => false,
                'required' => false
            ])
            ->add('rue', EntityType::class,[
                'class' => Lieu::class,
                'placeholder'=>'rue (choisir un lieu)',
                'choice_label' => 'rue',
                'label' => 'Rue : ',
                'mapped' => false,
                'required'=>false
            ])
           ->add('latitude', EntityType::class,[
               'class' => Lieu::class,
               'placeholder'=>'latitude (choisir un lieu)',
               'choice_label' => 'latitude',
               'label' => 'latitude : ',
               'mapped' => false,
               'required'=>false
           ])
           ->add('longitude', EntityType::class,[
               'class' => Lieu::class,
               'placeholder'=>'longitude (choisir un lieu)',
               'choice_label' => 'longitude',
               'label' => 'Longitude : ',
               'mapped' => false,
               'required'=>false
           ])
        ;

        /* $formModifierLieu = function (FormInterface $form, Ville $ville = null) {
             $lieu = (null === $ville) ? [] : $ville->getLieu();

             $form->add('lieu', EntityType::class, [
                 'class' => Lieu::class,
                 'choices' => $lieu,
                 'placeholder' => 'lieu (choisir une ville)',
                 'choice_label' => 'nom',
                 'required' => false
             ]);
         };

         $builder->get('Ville')->addEventListener(
             FormEvents::POST_SUBMIT,
             function (FormEvent $event) use ($formModifierLieu) {
                 $ville = $event->getForm()->getData();
                 $formModifierLieu($event->getForm()->getParent(), $ville);

             });

        $formModifierDetails = function (FormInterface $form, Lieu $lieu = null){
            $rue = (null === $lieu)? [] : $lieu->getRue();
            $latitude = (null === $lieu)? [] : $lieu->getLatitude();
            $longitude = (null === $lieu)? [] : $lieu->getLongitude();
            $form->add('rue', EntityType::class,[
                'class' => Lieu::class,
                'choices'=> $rue,
                'placeholder'=>'rue (choisir un lieu)',
                'choice_label' => 'rue',
                'required'=>false
            ]);
            $form->add('latitude', EntityType::class,[
                'class' => Lieu::class,
                'choices'=> $latitude,
                'placeholder'=>'latitude (choisir un lieu)',
                'choice_label' => 'latitude',
                'required'=>false
            ]);
            $form->add('longitude', EntityType::class,[
                'class' => Lieu::class,
                'choices'=> $longitude,
                'placeholder'=>'longitude (choisir un lieu)',
                'choice_label' => 'longitude',
                'required'=>false
            ]);

        };

        $builder->get('lieu')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifierDetails){
                $lieu = $event ->getForm()->getData();
                $formModifierDetails($event->getForm()->getParent(),$lieu);
            }
        );*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
