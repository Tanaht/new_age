<?php

namespace VisiteurBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtapeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array("label"=>"Nom de l'etape"))
        ->add('rechercher', SubmitType::class, array("label" => "Rechercher"));
    }


    public function getName()

    {

        return 'visiteurbundle_recherche_etape';

    }


}
