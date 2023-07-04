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
        $notification = null;

        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('old_password')->getData();

            if ($encoder->isPasswordValid($user, $old_pwd)) {

                $new_pwd = $form->get('new_password')->getData();
                $password = $encoder->encodePassword($user, $new_pwd);

                $user->setPassword($password);
                $entityManager->flush();
                $notification = "Votre mot de passe a bien été mis à jour.";
            } else {
                $notification = "Le mot de passe que vous avez saisi actuellement est incorrect";
            }
        }

        return $this->render('account/changepassword.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
