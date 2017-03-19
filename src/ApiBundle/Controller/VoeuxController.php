<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use VisiteurBundle\Entity\Cours;
use VisiteurBundle\Entity\Voeux;
use VisiteurBundle\Form\VoeuxForm;

class VoeuxController extends FOSRestController
{
    /**
     * @Post("/voeux/new/cours/{id}", requirements={"id":"\d+"})
     */
    public function newVoeuxAction(Request $request, $id) {

        $voeu = $request->get('datas');

        $voeu['cours'] = $id;
        $voeu['utilisateur'] = $this->getUser()->getId();

        $form = $this->createForm(VoeuxForm::class, new Voeux(), ['csrf_token_id' => 'voeu_form_token_id', 'csrf_field_name' => 'Token']);

        $form->submit($voeu, false);
        $form->handleRequest($request);

        $view = null;
        if($form->isSubmitted() && $form->isValid()) {
            /** @var $voeux Voeux */
            $voeux = $form->getData();

            $om = $this->getDoctrine()->getManager();
            $om->persist($voeux);
            $om->flush();


            $view = $this->view([], 200);
        }
        else {
            $view = $this->view($form->getErrors(true, false), 400);
        }

        return $this->handleView($view);

    }
}
