<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    /**
     * @Route("/profil/changepassword", name="app_change_password")
     */
    public function changePassword(): Response
    {
        return $this->render('account/changepassword.html.twig', [

        ]);
    }
}
