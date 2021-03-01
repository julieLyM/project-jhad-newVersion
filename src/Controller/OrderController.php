<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Service\Cart\CartServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ObjectManager;

class OrderController extends AbstractController
{

    /**
     * page commande
     * @Route("/commande", name="order", methods={"GET"})
     */

    public function index(CartServices $cartServices)
    {
        $date = new \DateTime();

        $order = new Order();
        $reference = $date->format('dmy');
        $order->setReference($reference);
        $order->setStatus(0);
        $order->setAmount($cartServices->getTotal());
        $order->setCreatedAt($date);
        $order->setUser($this->getUser());

        #l'utilisateur en cours

        $em =$this->getDoctrine()->getManager();

           $orders =$this->getDoctrine()
           ->getRepository(Order::class)
           ->findAll();
            $em->persist($order);


        //Enregistrer mes produits OrderDetails()
        //pour chaque produit du va iterer

        foreach ($cartServices->getFullCart() as $product)#Sur tout le panier qu'on récupère
        {
            $orderDetails = new OrderDetails();
            $orderDetails->setUserOrder($order);
            $orderDetails->setQuantity($product['quantity']);
            $orderDetails->setProduct($product['product']);

            //dd($orderDetails);
            $em =$this->getDoctrine()->getManager();
            $em->persist($orderDetails);
        }



        $em->flush();

        return $this->render('order/index.html.twig', [
            'orders'=>$orders,
            'products'=>$product
        ]);
    }
}
