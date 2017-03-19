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

        if($form->isSubmitted() && $form->isValid()) {
            dump('valid');
        }

        dump($voeu);
        dump($form);

    }
}
