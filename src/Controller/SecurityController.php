<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class SecurityController extends AbstractController
{
    private $session;
    public function __construct( SessionInterface $session) {
      $this->session = $session;
    }
    /**
     * @Route("/connexion", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Vérifier si l'utilisateur est connecté
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Rediriger vers une autre route
            return $this->redirectToRoute('app_profil');
        }

        $message = $this->session->getFlashBag()->get('success');
        $email = $this->session->getFlashBag()->get('email');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        if($email != null && $message != null) {
            $lastUsername = $email[0];
            $this->addFlash('notice',$message[0]);
        }
        if ($this->getUser()) {
            return $this->redirectToRoute('app_account');
        }

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/deconnexion", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    /**
     * @Route("/audio", name="audio_route")
     * @IsGranted("ROLE_ADMIN")
     */
    public function securityaudio(): Response
    {
        $html = '<html><body><h1>Admin</h1><p>Bonjour admin cette route est interdit au autre utilisateur</p></body></html>';
        return new Response($html, Response::HTTP_OK, [
            'Content-Type' => 'text/html',
        ]);

    }
    /**
     * @Route("/audio/{filename}", name="audio_route_file")
     */
    public function securityaudiofile(Request $request,$filename): Response
    {
        // Vérifier si l'utilisateur a le rôle "ROLE_ADMIN"
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Vous n\'avez pas l\'autorisation d\'accéder à ce fichier.');
        }

    }
}
