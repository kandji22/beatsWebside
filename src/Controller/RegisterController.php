<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em) {
    $this->em = $em;
    }
    /**
     * @Route("/inscription", name="register")
     */
    public function index( Request $request,UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $confirmPassword = $request->get('confirm_password');

            if($confirmPassword != $form->getData()->getPassword()) {
                //ici ajouter un message flash
                $this->addFlash('error', 'Le mot de passe et la confirmation doivent être identique.');

            }
            else {
                $password = $encoder->encodePassword($user,$user->getPassword());
                $user->setPassword($password);
                $user = $form->getData();
                $this->em->persist($user);
                $this->em->flush();
            }

        }
        return $this->render('register/index.html.twig', [
        'form' => $form->createView()
        ]);
    }
}
