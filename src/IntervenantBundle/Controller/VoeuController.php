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
        $om = $this->getDoctrine()->getManager();
        $etape = null;

        $form = $this->createForm(EtapeForm::class, null, ['attr' => ['action' => $this->generateUrl('visiteur_liste_enseignements')]]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $id = $form->get('identifiant')->getData();
            $etape = $om->getRepository('VisiteurBundle:Etape')->find($id);

            if ($etape == null ){
                /** @var FlashBagInterface $flashBag */
                $flashBag = $request->getSession()->getFlashBag();

                $flashBag->add('warning', 'Cette étape n\'existe pas');
            }
        }

        return $this->render('IntervenantBundle:Voeu:saisir.html.twig', [
            'etape' => $etape
        ]);
    }

}
