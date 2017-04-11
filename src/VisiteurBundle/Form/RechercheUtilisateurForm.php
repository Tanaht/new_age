<?php

namespace VisiteurBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
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
            ->add('identifiant', HiddenType::class)
        ;

        //Récuperation de l'identifiant du champ 'identifiant'
        $id = $builder->getForm()->createView()->children['identifiant']->vars['id'];

        $builder
            ->add('nom', TextType::class, array(
                "label" => "Nom de l'utilisateur",
                "attr" => [
                    "autocomplete" => "off",
                    "placeholder" => "Rechercher un utilisateur de NewAGE",
                    "typeahead" => null,
                    "display" => "display_name",
                    "url" => $this->router->generate('get_utilisateurs'),
                    "options" => "{id:'$id'}"
                ]
            ))
            ->add('rechercher',SubmitType::class, array("label"=>"Rechercher"))
        ;
    }

    public function getName()
    {
        return 'visiteur_bundle_recherche_utilisateur_form';
    }
}
