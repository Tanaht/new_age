<?php

namespace UserBundle\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use VisiteurBundle\Entity\Composante;
use VisiteurBundle\Form\EmailType;
use VisiteurBundle\Form\NumeroTelephoneType;

class UtilisateurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('composante', EntityType::class, [
                'disabled' => true,
                'class' => Composante::class,
                'choice_label' => 'nom',
            ])
            ->add('site_web', UrlType::class, [
                'required' => true,
                'attr' => ['placeholder' => 'http://www.exemple.com']
            ])
            ->add('bureau', TextType::class, ['required' => false])
            ->add('email_list', CollectionType::class, [
                'entry_type' => EmailType::class,
                'allow_add' => true,
                'allow_delete' => true
            ])
            ->add('num_list', CollectionType::class, [
                'entry_type' => NumeroTelephoneType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\Utilisateur'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'userbundle_utilisateur';
    }


}
