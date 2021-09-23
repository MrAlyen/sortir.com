<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\User;


class DesactiveFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('user', EntityType::class, [
            'class' => User::class,
            'label' => 'Utilisateur',
            'choice_label' => function (User $user) {
                                return $user->getNom() . ' ' . $user->getPrenom() . ' ( ' . $this->checkActive($user) . ' )';
                            }

        ])
        ->add('choice', ChoiceType::class, [
            'choices' => [
                'Activer' => true,
                'Desactiver' => false,
                'Supprimer' => false
            ],
            'expanded' => true,
            'label' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }

    private function checkActive($user): string
    {
        $active = $user->getActive();
        if($active){
            return "activé";
        } else {
            return "désactivé";
        }
    }
}
