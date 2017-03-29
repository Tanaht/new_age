<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 19/03/2017
 * Time: 03:32
 */

namespace VisiteurBundle\Form;


use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Validator\Constraints\Regex;
use Monolog\Handler\Curl\Util;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Entity\Utilisateur;
use VisiteurBundle\Entity\Cours;

class VoeuxForm extends AbstractType
{
    /**
     * @var EntityManager
     */
    private $em;
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

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
            ->add('nbHeures', IntegerType::class)
            ->add('commentaire', TextType::class)
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