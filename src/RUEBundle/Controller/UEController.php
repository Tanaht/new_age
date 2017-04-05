<?php

namespace RUEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UEController extends Controller
{
    public function GererUEsAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_RESP_UE', null, "Vous n'avez pas le droit d'accéder à cette page !");
        $usr= $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $ues = $em->getRepository('VisiteurBundle:UE')->findBy(array("responsable"=>$usr));
        return $this->render('RUEBundle:Default:gestion_ue.html.twig',
            array('ues' => $ues));
    }
}
