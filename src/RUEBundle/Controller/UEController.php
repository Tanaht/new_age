<?php

/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 15/03/2017
 * Time: 14:57
 */

namespace RUEBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UEController extends Controller
{
    public function gererUEAction(){
        $ues = $this->getDoctrine()->getRepository('VisiteurBundle:UE')->findAll();
        $respUEs = new ArrayCollection();
        foreach ($ues as $ue){
            if($ue->getResponsable() == $this->getUser()){
                $respUEs->add($ue);
            }
        }
        return $this->render('RUEBundle:UE:gererUE.html.twig', ["ues"=>$respUEs]);
    }
}