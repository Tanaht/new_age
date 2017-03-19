<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 19/03/2017
 * Time: 04:01
 */

namespace ApiBundle\Controller;


use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Normalizer\ArrayNormalizerInterface;
use FOS\RestBundle\Normalizer\Exception;

class CsrfTokenController extends FOSRestController
{
    /**
     * @Get("/csrf/token/{intention}", requirements={"intention":"\w+"})
     *
     */
    public function getCsrfTokenAction($intention) {
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->refreshToken($intention);

        $view = $this->view($token->getValue(), 200);

        return $this->handleView($view);
    }
}