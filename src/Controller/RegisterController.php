<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $em;
    private $session;
    public function __construct(EntityManagerInterface $em,SessionInterface $session) {
    $this->em = $em;
    $this->session = $session;

    }
    /**
     * @Route("/inscription", name="register")
     */
    public function index( Request $request,UserPasswordEncoderInterface $encoder): Response
    {
        // Vérifier si l'utilisateur est connecté
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Rediriger vers une autre route
            return $this->redirectToRoute('app_profil');
        }
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

                try {
                    // Code d'insertion de l'utilisateur ici
                    $password = $encoder->encodePassword($user,$user->getPassword());
                    $user->setPassword($password);
                    $user = $form->getData();
                    $this->em->persist($user);
                    $this->em->flush();

                    // Succès de l'insertion, afficher un message flash de succès
                    $this->session->getFlashBag()->add('success', 'L\' utilisateur: '.$user->getEmail().' à été bien crée');
                    $this->session->getFlashBag()->add('email', $user->getEmail());
                    return $this->redirectToRoute('app_login');
                } catch (UniqueConstraintViolationException $exception) {
                    // Erreur d'intégrité de contrainte, l'adresse e-mail existe déjà
                    $this->session->getFlashBag()->add('error', 'L\'adresse e-mail est déjà utilisée par un autre utilisateur.');
                } catch (\Exception $exception) {
                    // Autre erreur non prévue, afficher un message d'erreur générique
                    $this->session->getFlashBag()->add('error', 'Une erreur s\'est produite lors de la création du compte.');
                }


            }

        }
        return $this->render('register/index.html.twig', [
        'form' => $form->createView()
        ]);
    }
}
