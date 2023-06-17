<?php

namespace App\Controller;

use App\Classe\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $cart;
    public function __construct( Cart $cart) {
        $this->cart = $cart;
    }
    /**
     * @Route("/cart/add/{id}", name="add_to_cart")
     */
    public function add($id): Response
    {
        $this->cart->add($id);
        return $this->redirectToRoute('app_show');
    }

    /**
     * @Route("/cart/delete/{id}", name="delete_to_cart")
     */
    public function delete($id): Response
    {
        $this->cart->delete($id);
        return $this->redirectToRoute('app_account');
    }
}
