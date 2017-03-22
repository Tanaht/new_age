<?php
namespace UserBundle\DataFixtures;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use UserBundle\Entity\Role;
use UserBundle\Entity\Utilisateur;
use VisiteurBundle\Entity\Email;
use VisiteurBundle\Entity\NumeroTelephone;
use VisiteurBundle\Form\EmailType;

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
            'tel1' => '+33222113344',
            'tel2' => '+33622113344',
            'email' => 'charp.antoine@gmail.com',
            'site_web' => 'www.google.fr',
            'bureau' => null,
            'roleActuel' => 'ROLE_VISITEUR',
            'rolePossedee' => ['ROLE_VISITEUR', 'ROLE_ENSEIGNANT']
        ));


        $utilisateurs->add(array(
            'nom' => 'Mullier',
            'prenom' => 'Antoine',
            'username' => 'AntMu',
            'password' => '1234',
            'tel1' => '+33222113344',
            'tel2' => '+33622113344',
            'email' => 'antoinemullier@gmail.com',
            'site_web' => 'www.google.fr',
            'bureau' => 'D222',
            'roleActuel' => 'ROLE_VISITEUR',
            'rolePossedee' => ['ROLE_VISITEUR', 'ROLE_ENSEIGNANT']
        ));

        $utilisateurs->add(array(
            'nom' => 'Brossault',
            'prenom' => 'Guillaume',
            'username' => 'Yaatta',
            'tel1' => '02 22 11 33 44',
            'tel2' => '06 22 11 33 44',
            'password' => '1234',
            'email' => 'g.brossault@hotmail.fr',
            'site_web' => 'www.google.fr',
            'bureau' => 'D211',
            'roleActuel' => 'ROLE_VISITEUR',
            'rolePossedee' => ['ROLE_VISITEUR', 'ROLE_ENSEIGNANT']
        ));

        $utilisateurs->add(array(
            'nom' => 'Mendes Dos Santos',
            'prenom' => 'Alexandre',
            'username' => 'Morganol',
            'tel1' => '02 22 11 33 44',
            'tel2' => '06 22 11 33 44',
            'password' => '1234',
            'email' => 'truc@gmail.com',
            'site_web' => 'www.google.fr',
            'bureau' => 'D4242',
            'roleActuel' => 'ROLE_VISITEUR',
            'rolePossedee' => ['ROLE_VISITEUR', 'ROLE_ENSEIGNANT']
        ));

        $utilisateurs->forAll(function($index, array $info) use($manager) {
            $utilisateur = new Utilisateur();
            $utilisateur->setNom($info['nom']);
            $utilisateur->setPrenom($info['prenom']);
            $utilisateur->setUsername($info['username']);

            $email = new Email();
            $email->setEmail($info['email']);
            $utilisateur->addEmailList($email);

            $phone = new NumeroTelephone();
            $phone->setNumero($info['tel1']);
            $utilisateur->addNumList($phone);

            $phone = new NumeroTelephone();
            $phone->setNumero($info['tel1']);
            $utilisateur->addNumList($phone);

            $utilisateur->setSiteWeb($info['site_web']);
            $utilisateur->setBureau($info['bureau']);

            $utilisateur->setPassword($this->encoder->encodePassword($utilisateur, $info['password']));


            foreach ($info['rolePossedee'] as $key => $value) {
                $role = $manager->getRepository(Role::class)->findOneBy(['slug' => $value]);

                if($role != null)
                    $utilisateur->addRolePosseder($role);
            }

            $role = $manager->getRepository(Role::class)->findOneBy(['slug' => $info['roleActuel']]);

            if($role != null)
                $utilisateur->setRoleActuel($role);

            $repo_composante = $manager->getRepository("VisiteurBundle:Composante");
            $composante = $repo_composante->findOneBy(array("nom"=>"ISTIC"));
            $utilisateur->setComposante($composante);
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
