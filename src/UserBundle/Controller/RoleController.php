<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use UserBundle\Entity\Role;
use UserBundle\Entity\Utilisateur;

class RoleController extends Controller
{

    public function updateRoleAction(Request $request, Role $role) {
        /** @var Utilisateur $user */
        $user = $this->getUser();
        $om = $this->getDoctrine()->getManager();

        if(!$user->getRolePosseder()->contains($role)) {
            /** @var FlashBagInterface $flashBag */
            $flashBag = $request->getSession()->getFlashBag();

            $flashBag->add('danger', 'Vous ne pouvez pas utiliser ce rôle.');
        }
        else {
            $user->setRoleActuel($role);
            $om->persist($user);
            $om->flush();
            $token = new UsernamePasswordToken($user, null, "main", $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
        }


        if($request->get('url') == null)
            return $this->redirectToRoute('visiteur_homepage');

        return $this->redirect($request->get('url'));
    }
}
