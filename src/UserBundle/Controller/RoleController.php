<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\Role;

class RoleController extends Controller
{
    public function dropdownAction(Request $request)
    {
        $roles = $this->getDoctrine()->getRepository(Role::class)->findAll();
        return $this->render('@User/Role/dropdown.html.twig', ['roles' => $roles]);
    }
}
