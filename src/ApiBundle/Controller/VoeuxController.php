<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Normalizer\CamelKeysNormalizer;
use JMS\SerializerBundle\JMSSerializerBundle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use VisiteurBundle\Entity\Cours;
use VisiteurBundle\Entity\Voeux;
use VisiteurBundle\Form\VoeuxForm;

class VoeuxController extends FOSRestController
{
    /**
     * @Post("/voeux/new/cours/{id}", requirements={"id":"\d+"})
     */
    public function newVoeuxAction(Request $request, $id) {

        $view = null;
        $voeuObject = new Voeux();

        $postedVoeu = $request->get('datas');

        $postedVoeu['cours'] = $id;
        $postedVoeu['utilisateur'] = $this->getUser()->getId();


        if(!array_key_exists('nbHeures', $postedVoeu))
            $postedVoeu['nbHeures'] = null;

        if(!array_key_exists('commentaire', $postedVoeu))
            $postedVoeu['commentaire'] = null;

        $form = $this->createForm(VoeuxForm::class, $voeuObject, ['csrf_protection' => false]);//'csrf_protection' => false

        dump($postedVoeu);
        $form->submit($postedVoeu, false);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $om = $this->getDoctrine()->getManager();

            $om->persist($voeuObject);
            $om->flush();


            $view = $this->view([], 200);
        }
        else {
            $view = $this->view($form->getErrors(true, true), 400);
        }

        return $this->handleView($view);

    }

    /**
     * @Post("/voeux/edit/{id}", requirements={"id":"\d+"})
     */
    public function editVoeuxAction(Request $request, Voeux $voeux) {
        $view = null;
        $postedVoeu = $request->get('datas');

        //normalize data to match with VoeuxForm attented values --> it would be nice to create a service that automatically did the job from some configuration.
        if(array_key_exists('id', $postedVoeu))
            unset($postedVoeu['id']);

        if(array_key_exists('utilisateur', $postedVoeu) && array_key_exists('id', $postedVoeu['utilisateur']))
            $postedVoeu['utilisateur'] = $postedVoeu['utilisateur']['id'];

        if(array_key_exists('cours', $postedVoeu) && array_key_exists('id', $postedVoeu['cours']))
            $postedVoeu['cours'] = $postedVoeu['cours']['id'];

        if(!array_key_exists('nbHeures', $postedVoeu))
            $postedVoeu['nbHeures'] = null;

        if(!array_key_exists('commentaire', $postedVoeu))
            $postedVoeu['commentaire'] = null;

        $form = $this->createForm(VoeuxForm::class, $voeux, ['csrf_protection' => false]);//'csrf_protection' => false

        $form->submit($postedVoeu, false);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $om = $this->getDoctrine()->getManager();
            $om->persist($voeux);
            $om->flush();

            $view = $this->view([], 200);
        }
        else {
            $view = $this->view($form->getErrors(true, true), 400);
        }

        return $this->handleView($view);

    }
}
