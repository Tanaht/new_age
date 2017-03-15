<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 01/03/2017
 * Time: 14:30
 */

namespace EnseignantBundle\Entity;


use UserBundle\Entity\Utilisateur;

class UserVoeuxStats
{
    private $userName;
    private $servicesDus;
    private $nbVoeux;
    private $pourcentage;

    public function __construct(Utilisateur $user)
    {
        $this->userName = $user->getPrenom()." ".$user->getNom();
        $this->servicesDus = $user->getServiceDus();
        $this->nbVoeux = 0;
        foreach ($user->getVoeux() as $voeux){
            foreach ($voeux->getVoeux() as $cours){
                $this->nbVoeux += $cours->getNbh();
            }
        }
        $this->pourcentage = ($this->nbVoeux*100)/$this->servicesDus;
    }

    public function setUserName($userName){
        $this->userName = $userName;
        return $this;
    }

    public function getUserName(){
        return $this->userName;
    }

    public function setServiceDus($serviceDus){
        $this->servicesDus = $serviceDus;
        return $this;
    }

    public function getServiceDus(){
        return $this->servicesDus;
    }

    public function setNbVoeux($nbVoeux){
        $this->nbVoeux = $nbVoeux;
        return $this;
    }

    public function getNbVoeux(){
        return $this->nbVoeux;
    }

    public function setPourcentage($pourcentage){
        $this->pourcentage = $pourcentage;
        return $this;
    }

    public function getPourcentage(){
        return $this->pourcentage;
    }
}