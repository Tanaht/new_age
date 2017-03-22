<?php

namespace VisiteurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use VisiteurBundle\Entity\Etape;
use VisiteurBundle\Form\EtapeForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller pour l'affichage des enseignements
 */
class ListeEnseignementsController extends Controller
{
    public function listeEnseignementsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $etape = null;
        $form = $this->createForm(EtapeForm::class, null, ['attr' => ['action' => $this->generateUrl('visiteur_liste_enseignements')]]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $id = $form->get('identifiant')->getData();
            $etape = $em->getRepository('VisiteurBundle:Etape')->find($id);

            if ($etape == null ){
                /** @var FlashBagInterface $flashBag */
                $flashBag = $request->getSession()->getFlashBag();

                $flashBag->add('warning', 'Cette étape n\'existe pas');
            }
        }
        
        return $this->render("@Visiteur/Default/liste_enseignements.html.twig",
            array('etape' => $etape, 'form' => $form->createView()));
    }
}