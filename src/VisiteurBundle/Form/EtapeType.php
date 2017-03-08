<?php

namespace VisiteurBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Router;

class EtapeType extends AbstractType
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array(
                "label" => "Nom de l'étape",
                "attr" => [
                    "typeahead" => null,
                    "display" => "display_name",
                    "url" => $this->router->generate('get_etapes'),
                    "options" => "{id:'$id'}"
                ]
            ))
            ->add('rechercher',SubmitType::class, array("label"=>"Rechercher"))
        ;
    }

    public function getName(){

        return 'visiteurbundle_recherche_etape';

    }


}
