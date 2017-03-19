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
            $om = $this->getDoctrine()->getManager();

            /** @var $voeux Voeux */
            $voeux = $form->getData();
            $om->persist($voeux);
            $om->flush();


            $view = $this->view([], 200);
        }
        else {
            $view = $this->view($form->getErrors(true, false), 400);
        }

        return $this->handleView($view);

    }

    /**
     * @Post("/voeux/edit/{id}", requirements={"id":"\d+"})
     */
    public function editVoeuxAction(Request $request, Voeux $voeux) {



        $voeu = $request->get('datas');

        //normalize data to match with VoeuxForm attented values
        if(array_key_exists('id', $voeu))
            unset($voeu['id']);

        if(array_key_exists('utilisateur', $voeu) && array_key_exists('id', $voeu['utilisateur']))
            $voeu['utilisateur'] = $voeu['utilisateur']['id'];

        if(array_key_exists('cours', $voeu) && array_key_exists('id', $voeu['cours']))
            $voeu['cours'] = $voeu['cours']['id'];

        $form = $this->createForm(VoeuxForm::class, $voeux, ['csrf_token_id' => 'voeu_form_token_id', 'csrf_field_name' => 'Token']);

        $form->submit($voeu, false);
        $form->handleRequest($request);

        $view = null;
        if($form->isSubmitted() && $form->isValid()) {
            $om = $this->getDoctrine()->getManager();
            $voeux = $form->getData();
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
