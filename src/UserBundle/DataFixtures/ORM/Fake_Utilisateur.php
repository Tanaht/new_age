<?php
namespace UserBundle\DataFixtures;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use UserBundle\Entity\Utilisateur;
use VisiteurBundle\Entity\Email;

/**
 * Created by PhpStorm.
 * User: tanna
 * Date: 28/01/2017
 * Time: 16:04
 */
class Fake_Utilisateur implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var UserPasswordEncoder
     */
    private $encoder;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $utilisateurs = new ArrayCollection();

        $utilisateurs->add(array(
            'nom' => 'Charpentier',
            'prenom' => 'Antoine',
            'username' => 'Tanaky',
            'password' => '1234',
            'email' => 'charp.antoine@gmail.com'
        ));


        $utilisateurs->add(array(
            'nom' => 'Mullier',
            'prenom' => 'Antoine',
            'username' => 'AntMu',
            'password' => '1234',
            'email' => 'antoinemullier@gmail.com'
        ));


        $utilisateurs->forAll(function($index, array $info) use($manager) {
            $utilisateur = new Utilisateur();
            $utilisateur->setNom($info['nom']);
            $utilisateur->setPrenom($info['prenom']);
            $utilisateur->setUsername($info['username']);
            $utilisateur->addEmail(new Email($info['email'],$utilisateur));
            $utilisateur->setPassword($this->encoder->encodePassword($utilisateur, $info['password']));
            $manager->persist($utilisateur);
            return true;
        });
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->encoder = $container->get('security.password_encoder');
    }
}