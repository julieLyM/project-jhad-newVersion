<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/dashboard")
 */
class GestionOrderController extends AbstractController
{
    /**
     * @Route("/commande", name="gestion_order", methods={"GET|POST"})
     */
    public function index(): Response
    {
        $orders = $this->getDoctrine()
            ->getRepository(Order::class)
            ->findall();

        return $this->render('gestion_order/index.html.twig',[
            'orders'=>$orders
        ]);
    }
}
