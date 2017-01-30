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
            return $this->redirectToRoute("home");//TODO: redirect to mainpage when authenticated
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('@User/Default/connexion_form.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }

    public function temp_routeAction()
    {
        return $this->render("@User/Default/fake_home.html.twig");
    }
}
