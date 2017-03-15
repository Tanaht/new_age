<?php

namespace EnseignantBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use EnseignantBundle\Entity\UserVoeuxStats;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VoeuxController extends Controller
{
    public function indexAction()
    {
        return $this->render('EnseignantBundle:Default:index.html.twig');
    }

    public function bilanVoeuxParComposanteAction()
    {
        $composante = $this->getUser()->getComposante();
        $users = $this->getDoctrine()->getManager()->getRepository('UserBundle:Utilisateur')->findBy(['composante' => $composante]);
        $usersStats = new ArrayCollection();
        $totalServicesDus = 0;
        $totalVoeux = 0;
        foreach($users as $user){
            $userStat = new UserVoeuxStats($user);
            $usersStats->add($userStat);
            $totalServicesDus += $userStat->getServiceDus();
            $totalVoeux += $userStat->getNbVoeux();
        }
        $pourcentageTotal = (100*$totalVoeux)/$totalServicesDus;
        return $this->render('EnseignantBundle:Voeux:bilanParComposante.html.twig', ["users"=>$usersStats, "voeuxExprimes"=>$pourcentageTotal]);
    }
}
