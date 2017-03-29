<?php

namespace UserBundle\Controller;

use FOS\RestBundle\Context\Context;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UserBundle\Entity\Utilisateur;

class SecurityController extends Controller
{
    public function connexionAction(Request $request)
    {
        if($this->get('security.authorization_checker')->isGranted("IS_AUTHENTICATED_REMEMBERED")){

            $serializer = $this->get('fos_rest.serializer');
            $jsonUser = $serializer->serialize($this->getUser(), 'json', new Context());

            $cookie = new Cookie('profil', $jsonUser, 0, '/', false, false, false);
            $redirectResponse = $this->redirectToRoute("visiteur_homepage");
            $redirectResponse->headers->setCookie($cookie);
            return $redirectResponse;
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('@User/Default/connexion_form.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }
}
