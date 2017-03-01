<?php

namespace VisiteurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use VisiteurBundle\Entity\Etape;
use VisiteurBundle\Form\EtapeType;
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
        $form = $this->createForm(EtapeType::class, null, ['attr' => ['action' => $this->generateUrl('visiteur_liste_enseignements')]]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $name = $form->getData()['name'];
            $etape = $em->getRepository('VisiteurBundle:Etape')->findOneBy(['name'=>$name]);
        }
    if ($etape == null){
            /** @var FlashBagInterface $flashBag */
            $flashBag = $request->getSession()->getFlashBag();

            $flashBag->add('warning', 'Etape inconnue');
    }
        return $this->render("@Visiteur/Default/liste_enseignements.html.twig",
            array('etape' => $etape,
                'form' => $form->createView()));
    }
}