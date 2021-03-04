<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\Cart\CartServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    /**
     * @Route("/commande/merci/{stripeSessionId}", name="order_validate", methods={"GET|POST"})
     */
    public function index(CartServices $cartServices, $stripeSessionId)
    {
        $order = $this->getDoctrine()->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        #si la commande n'existe pas et si l'utilisateur n'est pas celui qui a passé la commande=>redirection(sécurité)
        if(!$order || $order->getUser() != $this->getUser()){
            
            return $this->redirectToRoute('home');
        }
        #Si le statut de la commande est 0 => n'est pas encore payée
        if($order->getStatus() == 0){

            #On remove le panier(on a besion d'importer la class Cartservice pour pouvoir supprimer le panier)
            $cartServices->remove();

            #On change la statut de la commande au statut suivant=>on envoie dans la base de données par la suite passant le statut à 1(Payée)
            $order->setStatus(1);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            #Envoie de mail de confirmation(à faire)

        }

        #Afficher les information à l'utilisateur
        return $this->render('order_success/index.html.twig', [
            'order'=>$order
        ]);
    }
}
