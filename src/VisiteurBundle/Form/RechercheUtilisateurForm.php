<?php

namespace VisiteurBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RechercheUtilisateurForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array("label"=>"Nom de l'utilisateur"))
            ->add('rechercher',SubmitType::class, array("label"=>"Rechercher"));
    }

    public function getName()
    {
        return 'visiteur_bundle_recherche_utilisateur_form';
    }
}
