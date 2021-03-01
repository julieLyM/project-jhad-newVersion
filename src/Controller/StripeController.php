<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\Cart\CartServices;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session/{reference}", name="create_session", methods={"GET|POST"})
     */
    public function index(CartServices $cartServices, $reference)#la référence  va nous permettre d'aller chercher la commande liée au paiement.
    {
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';
        $products_stripe = [];

        #on récupére les informations de la commande lié à la référence
        $order = $this->getDoctrine()
                ->getRepository(Order::class)
                ->findOneByReference($reference);

        foreach ($order->getDetails()->getValues() as $item){#on récupere les infos en faisant une boucle grace à la variable référence de la commande


            $products_stripe[] = [

                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $item->getProduct()->getPrice()*100,
                    'product_data' => [
                        'name' => $item->getProduct()->getName(),
                        'images' => [[$YOUR_DOMAIN . "/uploads/images/product/".$item->getProduct()->getImage()]],
                    ],
                ],
                'quantity' => $item->getQuantity(),
            ];

        }

        Stripe::setApiKey('sk_test_51IP7GQE2WWDKXNMo2nFwZtQqT4xJbN8cFfMnsFpI12bJMV3E0A3EmExDWlUSwGvrMcob7mWFXDXgarK5yis6J7Mc00f9FnkpUG');

        $checkout_session = Session::create([
            'customer_email'=>$this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [[
                $products_stripe
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $order->SetStripeSessionId($checkout_session->id);#On crée une variable qui va nous permettre de récupérér les indos de la commande payée via stripe
        #il faut envoyer l'information dans la base de données
        $em = $this->getDoctrine()->getManager();
        $em->flush();

     $response = new JsonResponse(['id' => $checkout_session->id]);
     return $response;
    }
}
