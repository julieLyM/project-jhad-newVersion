<?php

namespace App\Controller;


use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    /**
     * @Route("/boutique", name="home_boutique" , methods={"GET"})
     */
    public function index(): Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBySlug('boutique')->getProducts();

        return $this->render('products/index.html.twig', [

            'products' => $products,

        ]);
    }

    /**
     * @Route("/boutique/{id}", name="boutique_product" , methods={"GET"})
     */
    public function productDetails( $id)
   {
        /*On récupère les produits et pour cela on doit faire un requete sql => grace à l'orm doctrine et donc on abesionde l'entite manager */
       $product = $this->getDoctrine()
           ->getRepository(Product::class)->findOneById($id);

        if(!$product){

           return $this->redirectToRoute('home_boutique');

        }

       return $this->render('products/index.show.html.twig',[
            /*On va créer une variable qui va nous permettre d'afficher les produits coté  */
            'product'=>$product

       ]);
   }

}
