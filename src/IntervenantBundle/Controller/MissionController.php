<?php
namespace IntervenantBundle\Controller;

use IntervenantBundle\Form\RechercheMissionForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MissionController extends Controller
{
    public function affichageAction(Request $request, $page)
    {
        $itemsByPage = $this->getParameter('items_by_page');
        dump($page, $itemsByPage);

    	$manager = $this->getDoctrine()->getManager();
    	$repository = $manager->getRepository("IntervenantBundle:Mission");

    	$searchForm = $this->createForm(RechercheMissionForm::class, null, ["action" => $request->getUri()]);
        $searchForm->handleRequest($request);

        if($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchNom = $searchForm->get("nom")->getData();

            $missions = $repository->getMissionsFilteredByName($searchNom, $itemsByPage, $page);
        }
        else {
            $missions = $repository->findBy([], null, $itemsByPage, $itemsByPage * ($page -1));
        }



        dump($missions);
        return $this->render('IntervenantBundle:Missions:missions.html.twig', [
            'searchForm' => $searchForm->createView()
        ]);
    }
}
