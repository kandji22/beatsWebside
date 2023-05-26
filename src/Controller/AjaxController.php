<?php
namespace App\Controller;
use App\Entity\Instrumentals;
use App\Entity\Likes;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AjaxController extends AbstractController {
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

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
}