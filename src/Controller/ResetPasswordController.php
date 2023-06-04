<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

    }
    /**
     * @Route("/mot-de-passe-oubie", name="reset_password")
     */
    public function index( Request $request): Response
    {
        if($this->getUser()) {
            $this->redirectToRoute('app_home');
        }
        $email = $request->get('email');
        if($email) {
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($email);

            if($user) {
                //save token
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setCreatedAt(new \DateTime());
                $reset_password->setToken(uniqid());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

                //send mail user avec un lien de mise à jour mot de passe
                $mail = new Mail();
                //send($to_email, $to_name, $subject, $content,$tabFiles = null)
                $url = $this->generateUrl('update_password', [
                    'token' => $reset_password->getToken()
                ]);
                $content = "Bonjour ".$user->getFirstname()."<br/>Vous avez demandé à réinitialiser votre mot de passe sur le site webBeat.<br/><br/>";
                $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='".$url."'>mettre à jour votre mot de passe</a>.";
                $subject = "Réinitialiser votre mot de passe sur La WebBeat";
                $mail->send($user->getEmail(),$user->getUsername(),$subject,$content);
                $this->addFlash('notice', 'Vous allez recevoir dans quelques secondes un mail avec la procédure pour réinitialiser votre mot de passe.');

            }
            else {
                $this->addFlash('notice', 'Cette adresse email est inconnue.');
            }
        }
        return $this->render('reset_password/index.html.twig', [

        ]);
    }

    /**
     * @Route("/modifier-mon-mot-de-passe/{token}", name="update_password")
     */
    public function updatepasword($token): Response
    {

        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);
        dd($reset_password);
    }
}
