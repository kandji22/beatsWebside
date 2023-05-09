<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{
    /**
     * @Route("/profil/changepassword", name="app_change_password")
     */
    public function changePassword(Request $request,UserPasswordEncoderInterface $encoder,EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $s = $user;
        $form = $this->createForm(ChangePasswordType::class,$user);
        //dd($request->get('confirm_change_password'));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form->get('password')->getData();
            $newPassord = $request->get('new_password');
            $newConfirmPassord = $request->get('confirm_change_password');
dd($);
            if($encoder->isPasswordValid($user,$oldPassword)) {
dd('ok');
                $this->addFlash('error', 'L\'ancien mot de passe rentré n\'est pas correct');
                exit();
            }
            dd('ok');
            if($newPassord != $newConfirmPassord) {
                $this->addFlash('error', 'Le mot de passe de confirmation n\'est pas la même');
                exit();
            }

            $user->setPassword($newPassord);
            $entityManager->flush($user);
            $entityManager->persist();

        }
        return $this->render('account/changepassword.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
