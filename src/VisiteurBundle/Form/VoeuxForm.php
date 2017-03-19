<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 19/03/2017
 * Time: 03:32
 */

namespace VisiteurBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Entity\Utilisateur;
use VisiteurBundle\Entity\Cours;

class VoeuxForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class
            ])
            ->add('cours', EntityType::class, [
                'class' => Cours::class
            ])
            ->add('nbHeures', IntegerType::class, [
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(array(
            'data_class' => 'VisiteurBundle\Entity\Voeux',
        ));
    }
}