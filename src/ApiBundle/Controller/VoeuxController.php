<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Normalizer\CamelKeysNormalizer;
use JMS\SerializerBundle\JMSSerializerBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
     * @Post("/voeux/edit/{idRelatedCours}", requirements={"idRelatedCours":"\d+"})
     * @ParamConverter("cours", converter="doctrine.orm", options={ "id" = "idRelatedCours"})
     */
    public function editVoeuxAction(Request $request, Cours $cours) {
        $voeuxFiltered = $cours->getVoeux()->filter(function(Voeux $voeux) {
           return $voeux->getUtilisateur() === $this->getUser();
        });

        if($voeuxFiltered->count() == 1)
            $voeux = $voeuxFiltered[0];
        else {
            throw new HttpException(400,"Un problème est survenu lors de la gestion des voeux, il ne peux y avoir qu'un seul voeux émis par un utilisateur pour un cours donné");
        }

        $view = null;
        $postedVoeu = $request->get('datas');
        dump($postedVoeu);

        //normalize data to match with VoeuxForm attented values --> it would be nice to create a service that automatically did the job from some configuration.
        if(array_key_exists('id', $postedVoeu))
            unset($postedVoeu['id']);

        if(array_key_exists('utilisateur', $postedVoeu) && is_array($postedVoeu['utilisateur']) && array_key_exists('id', $postedVoeu['utilisateur']))
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
