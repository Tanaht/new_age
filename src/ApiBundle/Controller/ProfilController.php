<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use UserBundle\Entity\Utilisateur;

class ProfilController extends FOSRestController
{
    public function getProfilAction(){
    	//TODO : profilAction
        $user = $this->getUser();
	    if(!is_object($user)){
	      throw $this->createNotFoundException();
	    }
	    $user->eraseCredentials();

        $view = $this->view($user);
        return $this->handleView($view);
    }
}
