<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Albums;
use App\Repository\AlbumsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;



class IndexController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

    }
    /**
     * @Route("/", name="app_home")
     */
    public function index(Request $request,CacheInterface $cache,AlbumsRepository $repository): Response
    {
        $titleAlbum = null;
        $albumsId = null;

        if($request->query->get('album_id') != null) {
            $albumsId = $request->query->get('album_id');
            $albumChoice = $this->entityManager->getRepository(Albums::class)->find($albumsId);

            $titleAlbum = $albumChoice->getTitle();
        }
        $filterAlbums = $cache->get('filter_album',function(){
            $filter = [];
            $albums = $this->entityManager->getRepository(Albums::class)->findAll();
            foreach ($albums as $album) {
                $bool = $album->haveInstruimental();
                if(!$bool) {
                    array_push($filter,$album);
                }
            }
            return $filter;
        });

        return $this->render('home/index.html.twig', [
            'albums' => $filterAlbums,
            'albumsId' => $albumsId,
            'titleAlbum' => $titleAlbum
        ]);
    }

    /**
     * @Route("/show/{id}", name="app_show")
     */
    public function show(Albums $album,Cart $cart): Response
    {
        $instrumentals = $album->getInstrumentals();
        $isInSession = $cart->isallreadyadd($album->getId());
        return $this->render('home/show.html.twig', [
            'controller_name' => 'HomeController',
            'album' => $album,
            'instrumentals' => $instrumentals,
            'isInSession' => $isInSession
        ]);
    }

    /**
     * @Route("/card/show", name="app_show_card")
     */
    public function showCard(): Response
    {
        return $this->render('card/show.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }




    /**
     * @Route("/commande", name="order_command")
     */
    public function Order(): Response
    {
        return $this->render('order/index.html.twig', [

        ]);
    }


    /**
     * @Route("/apropos", name="app_about")
     */
    public function about(Request $request): Response
    {

        return $this->render('home/about.html.twig', [

        ]);
    }
}