<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\PdfUpload;
use App\Entity\Albums;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Qipsius\TCPDFBundle\Controller\TCPDFController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use ZipArchive;

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
    public function downloadpdf(PdfUpload $pdf,Request $request) {
        $idAlbums = json_decode($request->query->get('ids'));
        $pdfFiles = [];

        $pdfController = new Dompdf();

        foreach ($idAlbums as $key=>$val) {

            $album = $this->entityManager->getRepository(Albums::class)->find($val);
            $contrat = $album->getContrat();
            $filename = $pdf->getPDFContrat($album,$contrat,$this->entityManager);
            $pdfPath = $_SERVER['DOCUMENT_ROOT'] . 'uploads/contrats/' . $filename;
            if (file_exists($pdfPath)) {
                // Définir les en-têtes pour le téléchargement du fichier
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="' . basename($pdfPath) . '"');
                header('Content-Length: ' . filesize($pdfPath));

// Lire le fichier et l'envoyer au navigateur
                readfile($pdfPath);
            }
        }



    }

}
