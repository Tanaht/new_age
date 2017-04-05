<?php

namespace IntervenantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use VisiteurBundle\Form\EtapeForm;

class VoeuController extends Controller
{

    public function saisirAction(Request $request)
    {
        return $this->render('IntervenantBundle:Voeu:saisir.html.twig');
    }

    public function missionsAction(Request $request)
    {
        return $this->render('IntervenantBundle:Missions:missions.html.twig');
    }
}
