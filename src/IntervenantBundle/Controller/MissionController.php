<?php
namespace IntervenantBundle\Controller;

use IntervenantBundle\Form\RechercheMissionForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MissionController extends Controller
{
    public function affichageAction(Request $request, $statut, $page)
    {

        $itemsByPage = $this->getParameter('items_by_page');

    	$manager = $this->getDoctrine()->getManager();
    	$repository = $manager->getRepository("IntervenantBundle:Mission");

    	$searchForm = $this->createForm(RechercheMissionForm::class, null, ["action" => $request->getUri()]);
        $searchForm->handleRequest($request);

        if($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchNom = $searchForm->get("nom")->getData();

            $missions = $repository->getMissionsFilteredByName($searchNom, $itemsByPage, $page, $statut);
        }
        else {
            $missions = $repository->getMissionsFilteredByName("", $itemsByPage, $page, $statut);
        }


        $count = $repository->countByStatus($statut);

        return $this->render('IntervenantBundle:Missions:missions.html.twig', [
            'searchForm' => $searchForm->createView(),
            'missions' => $missions,
            'page' => $page,
            'count' => $count
        ]);
    }
}
