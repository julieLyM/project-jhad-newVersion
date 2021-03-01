<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OrderCancelController extends AbstractController
{
    /**
     * @Route("/commande/erreur/{stripeSessionId}", name="order_cancel", methods={"GET|POST"})
     */

    public function index($stripeSessionId)
    {
        $order = $this->getDoctrine()->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if(!$order || $order->getUser() != $this->getUser()){#sécurité

            return $this->redirectToRoute('home');

        }

        #envoi email à l'utilisateur =>paiement n'est pas passé
        return $this->render('order_cancel/index.html.twig',[
            'order'=>$order
        ]);
    }
}
