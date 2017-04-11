<?php

namespace IntervenantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Routing\Router;

class RechercheMissionForm extends AbstractType
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
                "label" => "Nom de la mission",
                "attr" => [
                    "autocomplete" => "off",
                    "placeholder" => "Rechercher une mission",
                    "typeahead" => null,
                    "display" => "name",
                    "url" => $this->router->generate('get_missions'),
                ]
            ))
            ->add('rechercher',SubmitType::class, array("label"=>"Rechercher"))
        ;
    }

    public function getName()
    {
        return 'intervenant_bundle_recherche_mission_form';
    }
}
