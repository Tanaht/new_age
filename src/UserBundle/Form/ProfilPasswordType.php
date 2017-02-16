<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 16/02/2017
 * Time: 19:10
 */

namespace UserBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use UserBundle\Entity\Utilisateur;

class ProfilPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Mot de passe actuel',
                'mapped' => false,
                'constraints' => [
                    new UserPassword([
                        'message' => 'Le mot de passe est incorrecte'
                    ])
                ]
            ])
            /*
             * A cause de la contrainte du champ 'current_password,
             * impossible de mapper le champ password  à l'entité:
             * Symfony Form va hydrater l'entité avec les données du formulaire puis effectuer la validation sur l'entité directement
             * --> résultat UserPasswordConstraint toujours en erreur
             *          car on enregistre le nouveau mot de passe avant de tester
             *          si currentPassword correspond au mot de passe enregistré
             */
            ->add('password', RepeatedType::class, [
                'first_options' => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Confirmer le nouveau mot de passe'],
                'mapped' => false,
                'type' => PasswordType::class
            ])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}