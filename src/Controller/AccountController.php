<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Albums;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/profil/compte", name="app_account")
     */
    public function index(SessionInterface $session,Cart $cart): Response
    {
        $tabAlbumInCart = array();
        $tabCart = $cart->get();
        foreach ($tabCart as $key => $vay) {
            $album = $this->entityManager->getReference(Albums::class,$key);
            array_push($tabAlbumInCart,$album);
        }


        return $this->render('account/index.html.twig', [
            'albumsInCarts' => $tabAlbumInCart
        ]);
    }





}
