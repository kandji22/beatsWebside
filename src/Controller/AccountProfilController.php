<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;


use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountProfilController extends AbstractController
{

    /**
     * @Route("/profil/profile", name="app_profil")
     */
    public function changeProfil(Request $request,EntityManagerInterface $entityManager): Response
    {
        $notification = null;

        $user = $this->getUser();
        $form = $this->createForm(ProfilType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $request->request->all();
            $file = $request->files->get('picture');

            /*$errors = $validator->validate($file, [
                // Les contraintes de validation définies dans votre entité/formulaire
            ]);
            if (count($errors) > 0) {
                // Il y a des erreurs de validation, à vous de décider quoi faire
            }*/
            // Déplacer le fichier dans le répertoire de destination
            //$destination = '/public/images/profil';
            //$fileName = $file->getClientOriginalName(); // Nom d'origine du fichier
            //$file->move($destination, $fileName);
            //$notification = "Votre mot de passe a bien été mis à jour.";

            $entityManager->persist($user);
            $entityManager->flush();

        }

        return $this->render('account/profil.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
