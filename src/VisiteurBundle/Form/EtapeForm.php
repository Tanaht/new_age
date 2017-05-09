<?php

namespace VisiteurBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Router;

class EtapeForm extends AbstractType
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
            ->add('identifiant', HiddenType::class)
        ;

        //Récuperation de l'identifiant du champ 'identifiant'
        $id = $builder->getForm()->createView()->children['identifiant']->vars['id'];

        $builder
            ->add('nom', TextType::class, array(
                "label" => false,
                "attr" => [
                    "typeahead" => null,
                    "autocomplete" => "off",
                    "placeholder" => "Rechercher une étape...",
                    "display" => "name",
                    "url" => $this->router->generate('get_etapes'),
                    "options" => "{id:'$id'}"
                ]
            ))
            ->add('rechercher',SubmitType::class, [
                "label"=>"Rechercher",
                "attr" => ['class' => 'btn-primary']
            ])
        ;
    }

    public function getName(){

        return 'visiteurbundle_recherche_etape';

    }


}
