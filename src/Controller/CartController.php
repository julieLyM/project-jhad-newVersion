<?php

namespace App\Controller;


use App\Service\Cart\CartServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="cart_index", methods={"GET"})
     */
    public function index(CartServices $cartServices)
    {

        return $this->render('cart/index.html.twig', [
            'items'=>$cartServices->getFullCart(),
            'total'=>$cartServices->getTotal()
        ]);

    }

    /**
     * @Route("/panier/add/{id}", name="cart_add", methods={"GET|POST"})
     */
    public function add($id, CartServices $cartServices)
    {
        $cartServices->add($id);

        return  $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/panier/add/{id}", name="cart_remove", methods={"GET|POST"})
     */
    public function remove($id, CartServices $cartServices)
    {
        $cartServices->remove($id);

        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/panier/decrease/{id}", name="cart_decrease", methods={"GET|POST"})
     */
    public function decrease(CartServices $cartServices, $id)
    {
        $cartServices->decrease($id);

        return $this->redirectToRoute('cart_index');
    }
}
