<?php


namespace App\Service\Cart;


use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartServices
{
    private $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }


    public function add($id )
{
    $panier = $this->session->get('panier', []);#si je n'ai pas encore un panier dans la session , prenons un panier un tableau vide = > un tableau associatif

    #on rajoute les produits dans le panier
    if(!empty($panier[$id])){

        $panier[$id]++;#si on rajoute le même produit il se rajout au produit que l'on avait dans le panier
    }else{
        $panier[$id] = 1;
    }

    #Remettre le panier dans la session pour le sauvegarder
    $this->session->set('panier', $panier);
}

    public function delete($id)
{
    $panier= $this->session->get('panier', []);

    if(!empty($panier[$id])){#s'il existe

        unset($panier[$id]);

    }

    $this->session->set('panier', $panier);
}

    public function remove()
    {
        return $this->session->remove('panier');
    }

    public function getFullCart(){

    $panier = $this->session->get('panier', []);

    #enrichir les informations

    $panierWithData = [];

    foreach($panier as $id => $quantity){#a chaque fois qu'il boucle il rajoute une nouvelle entrée


        $panierWithData[] = [
            'product'=> $this->productRepository->find($id),
            'quantity'=>$quantity
        ];

    }

    return $panierWithData;
}
    public function decrease($id)
    {
        $panier = $this->session->get('panier', []);
        //verifier si la quantité de notre produit est égal à 1

        if($panier[$id]> 1){
            //retirer une quantité
            $panier[$id]--;

        }else{
            //supprimer le produit
            unset($panier[$id]);

        }
        return $this->session->set('panier', $panier);#tu me reset le nouveau cart apres la suppression ou/et retrait d'un produit
    }

public function getTotal()

    {
        $total = 0; #déclaration d'une variable pour calculer une variable

    foreach ($this->getFullCart() as $item){
        $total += $item['product']->getPrice() * $item['quantity'];


    }
        return $total;
    }

}