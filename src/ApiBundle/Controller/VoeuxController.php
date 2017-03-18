<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use VisiteurBundle\Entity\Cours;

class VoeuxController extends FOSRestController
{
    /**
     * @Post("/voeux/new/cours/{id}", requirements={"id":"\d+"})
     */
    public function newVoeuxAction(Request $request, Cours $cours) {

    }
}
