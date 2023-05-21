<?php

namespace App\Controller;

use App\Entity\Albums;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function index(): Response
    {
        $albums = $this->entityManager->getRepository(Albums::class)->findAll();

        return $this->render('home/index.html.twig', [
            'albums' => $albums
        ]);
    }

    /**
     * @Route("/show", name="app_show")
     */
    public function show(): Response
    {
        return $this->render('home/show.html.twig', [
            'controller_name' => 'HomeController',
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

    //delete_to_cart
    /**
     * @Route("/card/delete{id}", name="delete_to_cart")
     */
    public function deleteCard(): Response
    {
    }


    /**
     * @Route("/commande", name="order_command")
     */
    public function Order(): Response
    {
        return $this->render('order/index.html.twig', [

        ]);
    }
}