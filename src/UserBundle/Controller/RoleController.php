<?php

namespace UserBundle\Controller;

use FOS\RestBundle\Context\Context;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
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
        }

        $serializer = $this->get('fos_rest.serializer');
        $jsonUser = $serializer->serialize($this->getUser(), 'json', new Context());

        $cookie = new Cookie('profil', $jsonUser, 0, '/', false, false, false);
        $redirectResponse = $this->redirectToRoute("visiteur_homepage");
        $redirectResponse->headers->setCookie($cookie);

        return $redirectResponse;

    }
}
