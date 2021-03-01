<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Service\Cart\CartServices;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/commande", name="order", methods={"GET|POST"})
     */

    public function index(CartServices $cartServices)
    {

            $date = new \DateTime();


            $order = new Order();
            $reference = $date->format('dmy').'-'.uniqid('', true);
            $order->setReference($reference);
            $order->setStatus(0);
            $order->setAmount($cartServices->getTotal());
            $order->setCreatedAt($date);
            $order->setUser($this->getUser());#l'utilisateur en cours

            $em =$this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            //Enregistrer mes produits OrderDetails()
            //pour chaque produit du va iterer



            foreach ($cartServices->getFullCart() as $item)#Sur tout le panier qu'on récupère
            {
                $orderDetails = new OrderDetails();
                $orderDetails->setUserOrder($order);
                $orderDetails->setQuantity($item['quantity']);
                $orderDetails->setProduct($item['product']);

                $em =$this->getDoctrine()->getManager();
                $em->persist($orderDetails);

            }

            $em->flush();

            return $this->render('order/index.html.twig', [
                'cart'=>$cartServices->getFullCart(),
                'order'=>$order,
                'reference'=>$order->getReference()#On passe la variable réference pour pouvoir y avoir accés et l'utiliser dans la recherche de la commande liée au paiement
            ]);


        }

}
