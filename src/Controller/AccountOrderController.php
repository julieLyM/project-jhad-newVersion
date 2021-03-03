<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccountOrderController extends AbstractController
{
    /**
     * @Route("/compte/commande", name="account_order", methods={"GET"})
     */
    public function index()
    {
        return $this->render('account/order.html.twig');
    }
}
