<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/compte", name="app_account")
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [

        ]);
    }

    /**
     * @Route("/profile", name="app_profil")
     */
    public function profil(): Response
    {
        return $this->render('account/profil.html.twig', [

        ]);
    }
}
