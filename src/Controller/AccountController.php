<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\PdfUpload;
use App\Entity\Albums;
use App\Entity\Instrumentals;
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
    private $user;
    public function __construct(EntityManagerInterface $entityManager,Security $security) {
        $this->entityManager = $entityManager;
        $this->user = $security->getUser();
    }

    /**
     * @Route("/profil/compte", name="app_account")
     */
    public function index(SessionInterface $session,Cart $cart,Security $security): Response
    {
        $pdf = new PdfUpload();
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
            $filename = $pdf->getPDFContrat($album,$contrat,$this->entityManager,$this->user);
            $pdfPath = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/contrats/' . $filename;
            if (file_exists($pdfPath)) {
                $response = new Response();

                // Définir les en-têtes pour le téléchargement du fichier
                $response->headers->set('Content-Type', 'application/pdf');
                $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($pdfPath) . '"');
                $response->headers->set('Content-Length', filesize($pdfPath));

                // Lire le fichier et le renvoyer dans la réponse
                $response->setContent(file_get_contents($pdfPath));

                return $response;
            }
        }
        // Si aucun fichier PDF n'est disponible, rediriger vers la même page avec un message d'erreur
        $this->addFlash('error', 'Aucun fichier PDF disponible.');

        return $this->redirectToRoute('app_account');
    }

    /**
     * @Route("/profil/album", name= "download_album")
     */
    public function downloadAlbum(Request $request) {
        $idAlbums = json_decode($request->query->get('ids'));
        if(empty($idAlbums)) {
            return ;
        }

        $album = $this->entityManager->getRepository(Albums::class)->find($idAlbums[0]);
        $instrumentals = $album->getInstrumentals();
        $zipFilename = $_SERVER['DOCUMENT_ROOT'] . 'myzipdoc/archive.zip';
        $zip = new ZipArchive();
        if ($zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Impossible de créer le fichier ZIP.');
        }
        foreach ($instrumentals as $instrumental) {
            $filePath = $_SERVER['DOCUMENT_ROOT'] . 'original/'.$instrumental->getOriginalfile();
            $filename = basename($filePath);
            $zip->addFile($filePath, $filename);
        }
        $zip->close();

        // Envoyer le fichier Zip au navigateur en tant que réponse
        return new BinaryFileResponse($zipFilename, 200, [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="audio_archive.zip"',
        ]);

    }

    /**
     * @Route("/profil/music", name="music_download")
     */
    public function downloadMusic(Request $request)
    {
        $idInstru = $request->query->get('ids');

        if($idInstru == "") {
            return ;
        }

        $instru = $this->entityManager->getRepository(Instrumentals::class)->find($idInstru);



        // Vérifier si le fichier MP3 existe
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/public/original/'.$instru->getOriginalfile();

        if (!file_exists($filePath)) {
            // Retourner une réponse appropriée si le fichier n'existe pas
            return new Response('Fichier non trouvé.', 404);
        }
        $info = pathinfo($instru->getOriginalfile());
        $extension = $info['extension'];
        $filename = $info['basename'];


        // Envoyer le fichier MP3 au navigateur en tant que réponse
        return new BinaryFileResponse($filePath, 200, [
            'Content-Type' => 'audio/'.$extension,
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

}
