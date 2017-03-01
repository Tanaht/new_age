<?php

namespace VisiteurBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Router;

class RechercheUtilisateurForm extends AbstractType
{

    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array(
                "label"=>"Nom de l'utilisateur",
                "attr" => [
                    "typeahead" => null,
                    "display" => "'username'",
                    "url" => "'" . $this->router->generate('get_utilisateurs') . "'",
                ]
            ))
            ->add('identifiant', HiddenType::class)
            ->add('rechercher',SubmitType::class, array("label"=>"Rechercher"));
    }

    public function getName()
    {
        return 'visiteur_bundle_recherche_utilisateur_form';
    }
}
