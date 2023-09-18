<?php
namespace App\Controller;
use App\Entity\Instrumentals;
use App\Entity\Likes;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AjaxController extends AbstractController {
    private $entityManager;
    private $kernel;
    public function __construct(EntityManagerInterface $entityManager,KernelInterface $kernel)
    {
        $this->entityManager = $entityManager;
        $this->kernel = $kernel;

    }
    /**
     * @Route("/ajax", name="app_ajax_sound")
     */
    public function index(Request $request,Security $security): Response
    {
        
        $idInstrumental = $request->request->get('id');
        $like_instrumental = $request->request->get('checked');
        $idUtilisateur = $security->getUser()->getId();

        if($idUtilisateur == null) {
            return new Response('Merci de ce connecter');
        }
        $likesRepository = $this->entityManager->getRepository(Likes::class);

        $isLikeExist = $likesRepository->isLikedByUser($idUtilisateur,$idInstrumental);

        //l'utilisateur n'a pa encore aimé l'instrumental
        if(!$isLikeExist) {
            $user = $this->entityManager->getReference(User::class, $idUtilisateur);
            $instrumental = $this->entityManager->getReference(Instrumentals::class, $idInstrumental);
            $like = new Likes();
            $like->setUserId($user);
            $like->setInstrumentalId($instrumental);
            $like->setLikeInstrumental(true);
            //$this->entityManager->persist($objectInstrumental);
            //$this->entityManager->persist($user);
            $this->entityManager->persist($like);
            $this->entityManager->flush();
        }
        else {
           $tabLike = $likesRepository->getLike($idUtilisateur,$idInstrumental);
           $like = $tabLike[0];

           $this->entityManager->remove($like);
           $this->entityManager->flush();
        }
        return new Response("Merci");

    }

    /**
     * @Route("/ajax/profil", name="upload_image_profil")
     */
    public function ajaxprofil(Request $request,Security $security): Response
    {
        // Traitement de l'upload de l'image
        $uploadedFile = $request->files->get('fileUpload');

        // Vérifier si un fichier a été envoyé
        if (!$uploadedFile) {
            return new Response('Aucun fichier n\'a été envoyé.', Response::HTTP_BAD_REQUEST);
        }

        // Vérifier si le fichier est une image
        if (!in_array($uploadedFile->getMimeType(), ['image/jpeg', 'image/png'])) {
            return new Response('Le fichier envoyé n\'est pas une image valide.', Response::HTTP_BAD_REQUEST);
        }

        // Déplacer le fichier vers le répertoire souhaité (par exemple, le dossier public/images/profil)
        $user = $security->getUser();
        $timestamp = time();
        $newFileName = $user->getId().'.'.$timestamp.''.$uploadedFile->getClientOriginalExtension();
        $project_dir = $this->kernel->getProjectDir();
        $folder = '/images/profil/';
        $profilFile = $project_dir . '/public' . $folder . $newFileName;

        // Supprimer l'ancienne image si elle existe
        $files = scandir($project_dir . '/public' . $folder);
        foreach ($files as $file) {
            if (strpos($file, strval($user->getId())) === 0) {
                // Fichier trouvé ! Faites ce que vous voulez avec le fichier
                $filePath =  $project_dir . '/public' . $folder . $file;
                unlink($filePath);
            }
        }
        $user->setPicture($newFileName);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $uploadedFile->move($project_dir . '/public' . $folder, $newFileName);

        // Construire la réponse avec le nouveau chemin de l'image
        $imagePath = $folder . $newFileName;
        $response = new JsonResponse(['imagePath' => $imagePath]);

        // Retourner la réponse JSON
        return $response;
    }
}