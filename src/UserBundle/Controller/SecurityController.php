<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
    public function connexionAction()
    {
        if($this->get('security.authorization_checker')->isGranted("IS_AUTHENTICATED_REMEMBERED")){
            return $this->redirectToRoute("visiteur_homepage");
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('@User/Default/connexion_form.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }
}
