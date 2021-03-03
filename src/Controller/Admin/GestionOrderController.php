<?php

namespace App\Controller\Admin;

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
    public function index()
    {
        $orders = $this->getDoctrine()
            ->getRepository(Order::class)
            ->findAll();

        return $this->render('admin/gestion_order/index.html.twig',[
            'orders'=>$orders,
        ]);
    }
}
