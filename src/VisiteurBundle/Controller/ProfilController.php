<?php

namespace VisiteurBundle\Controller;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use UserBundle\Entity\Utilisateur;
use UserBundle\Form\ProfilDescriptionType;
use UserBundle\Form\ProfilGeneralInformationsType;
use UserBundle\Form\ProfilImageType;
use UserBundle\Form\ProfilPasswordType;
use VisiteurBundle\Entity\Email;
use VisiteurBundle\Entity\Etape;
use VisiteurBundle\Entity\NumeroTelephone;
use VisiteurBundle\Repository\EtapeRepository;
use VisiteurBundle\Form\RechercheUtilisateurForm;

/**
 * Controller qui gère la gestion des profils utilisateurs
 */
class ProfilController extends Controller
{
    private function handleProfilGeneraleInformationsForm(Request $request, Form $form, Utilisateur $utilisateur, ObjectManager $om, $modalTarget) {
        /** @var FlashBagInterface $flashBag */
        $flashBag = $request->getSession()->getFlashBag();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if($form->isValid()) {
                $om = $this->getDoctrine()->getManager();

                $om->persist($utilisateur);
                $om->flush();
                $flashBag->add('success', 'Modification effectuée avec succès');
                return true;
            }

            $flashBag->add('warning', "<a data-toggle='modal' href='#$modalTarget'>Une erreur est survenue sur le formulaire général</a>");
        }

        return false;
    }

    private function handleProfilDescriptionForm(Request $request, Form $form, Utilisateur $utilisateur, ObjectManager $om)
    {
        /** @var FlashBagInterface $flashBag */
        $flashBag = $request->getSession()->getFlashBag();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if($form->isValid()) {
                $om->persist($utilisateur);
                $om->flush();
                $flashBag->add('success', 'Modification effectuée avec succès');
                return true;
            }

            $flashBag->add('warning', 'Une erreur est survenue sur le formulaire de description');
        }

        return false;
    }

    private function handleProfilImageForm(Request $request, Form $form, Utilisateur $utilisateur, ObjectManager $om)
    {
        /** @var FlashBagInterface $flashBag */
        $flashBag = $request->getSession()->getFlashBag();
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            if ($form->isValid()) {
                // $file stores the uploaded PDF file
                /** @var UploadedFile $file */
                $file = $utilisateur->getFile();

                // Generate a unique name for the file before saving it
                $imageName = md5(uniqid()).'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                $file->move(
                    $this->getParameter('images_directory'),
                    $imageName
                );

                $utilisateur->setImage($imageName);

                $om->persist($utilisateur);
                $om->flush();
                $flashBag->add('success', 'Modification effectuée avec succès');
                return true;
            }

            $flashBag->add('warning', 'Une erreur est survenue sur le formulaire d\'upload');
        }
        return false;
    }

    private function handleProfilPasswordForm(Request $request, Form $form, Utilisateur $utilisateur, ObjectManager $om, $modalTarget)
    {
        /** @var FlashBagInterface $flashBag */
        $flashBag = $request->getSession()->getFlashBag();
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            if ($form->isValid()) {
                //retrieve password string in profilPasswordForm arborescence
                $newPassword = $form->get('password')->get('first')->getData();

                $encoder = $this->get('security.encoder_factory')->getEncoder($utilisateur);

                $utilisateur->setPassword($encoder->encodePassword($newPassword, null));

                $om->persist($utilisateur);
                $om->flush();
                $flashBag->add('success', 'Modification effectuée avec succès');
                return true;
            }
            $flashBag->add('warning', "<a data-toggle='modal' href='#$modalTarget'>Une erreur est survenue sur le formulaire de modification du mot de passe</a>");
        }

        return false;
    }

    public function monProfilAction(Request $request)
    {
        $utilisateur = $this->getUser();

        $profilGeneralInformationsFormModalTarget = 'modif-profil-general';
        $profilPasswordFormModalTarget = 'modif-profil-mdp';

        $profilGeneralInformationsForm = $this->createForm(ProfilGeneralInformationsType::class, $utilisateur, ['action' => $request->getUri()]);
        $profilDescriptionForm = $this->createForm(ProfilDescriptionType::class, $utilisateur, ['action' => $request->getUri()]);
        $profilPasswordForm = $this->createForm(ProfilPasswordType::class, $utilisateur, ['action' => $request->getUri()]);
        $profilImageForm = $this->createForm(ProfilImageType::class, $utilisateur, ['action' => $request->getUri()]);

        $om = $this->getDoctrine()->getManager();

        if($request->isMethod('POST')) {
            if($this->handleProfilGeneraleInformationsForm($request, $profilGeneralInformationsForm, $utilisateur, $om, $profilGeneralInformationsFormModalTarget))
                return $this->redirectToRoute("visiteur_homepage");//POST REDIRECT GET (see: https://fr.wikipedia.org/wiki/Post-redirect-get)

            if($this->handleProfilDescriptionForm($request, $profilDescriptionForm, $utilisateur, $om))
                return $this->redirectToRoute("visiteur_homepage");//POST REDIRECT GET (see: https://fr.wikipedia.org/wiki/Post-redirect-get)

            if($this->handleProfilPasswordForm($request, $profilPasswordForm, $utilisateur, $om, $profilPasswordFormModalTarget))
                return $this->redirectToRoute("visiteur_homepage");//POST REDIRECT GET (see: https://fr.wikipedia.org/wiki/Post-redirect-get)

            if($this->handleProfilImageForm($request, $profilImageForm, $utilisateur, $om))
                return $this->redirectToRoute("visiteur_homepage");//POST REDIRECT GET (see: https://fr.wikipedia.org/wiki/Post-redirect-get)
        }

        return $this->render("@Visiteur/Default/mon_profil.html.twig", [
            'profilGeneralInformationsFormModalTarget' => $profilGeneralInformationsFormModalTarget,
            'profilPasswordFormModalTarget' => $profilPasswordFormModalTarget,
            'profilGeneralInformationsForm' => $profilGeneralInformationsForm->createView(),
            'profilDescriptionForm' => $profilDescriptionForm->createView(),
            'profilPasswordForm' => $profilPasswordForm->createView(),
            'profilImageForm' => $profilImageForm->createView(),
        ]);
    }

    public function consulterProfilsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = null;
        $form = $this->createForm(RechercheUtilisateurForm::class, null, ['attr' => ['action' => $request->getUri()]]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $id = $form->getData()['identifiant'];
            $user = $em->getRepository('UserBundle:Utilisateur')->findOneBy(['id' => $id]);
        }

        return $this->render('VisiteurBundle:Profil:profils.html.twig', [
            "user"=>$user,
            "rechercherUtilisateurForm"=>$form->createView()
        ]);
    }
}