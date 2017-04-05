<?php
namespace IntervenantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use VisiteurBundle\Form\EtapeForm;

class MissionController extends Controller
{
    public function affichageAction(Request $request,$page)
    {
    	$manager = $this->getDoctrine()->getManager();
    	$missions = $manager->getRepository("IntervenantBundle:Mission");

    	

        $composante = $repo_composante->findOneBy(array("nom"=>"ISTIC"));

        return $this->render('IntervenantBundle:Missions:missions.html.twig');
    }
}
