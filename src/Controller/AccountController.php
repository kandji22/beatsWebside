<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Pdf;
use App\Entity\Albums;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AccountController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/profil/compte", name="app_account")
     */
    public function index(SessionInterface $session,Cart $cart,Security $security): Response
    {
        $tabAlbumInCart = array();
        $tabCart = $cart->get();
        if($tabCart != null) {
            foreach ($tabCart as $key => $vay) {
                $album = $this->entityManager->getReference(Albums::class, $key);
                array_push($tabAlbumInCart, $album);
            }
        }
        //beat propriétaire findAlbumForUser
        $user = $security->getUser();
        $albumChoices = $this->entityManager->getRepository(Albums::class)->findAlbumForUser(true,$user);

        return $this->render('account/index.html.twig', [
            'albumsInCarts' => $tabAlbumInCart,
            'albumsChoices' => $albumChoices
        ]);
    }

    /**
     * @Route("/profil/pdf", name= "download_pdf")
     */
    public function downloadpdf(Pdf $pdf,Request $request) {
        $idAlbums = $request->request->get('ids');

        foreach ($idAlbums as $key=>$val) {

            $album = $this->entityManager->getRepository(Albums::class)->find($val);
            $contrat = $album->getContrat();
            $pdf->getPDFContrat($album,$contrat,$this->entityManager);
        }
        $response = new JsonResponse(['success']);
        return $response;
    }


}
