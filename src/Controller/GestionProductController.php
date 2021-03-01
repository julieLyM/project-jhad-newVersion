<?php

namespace App\Controller;


use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/dashboard")
 */

class GestionProductController extends AbstractController
{

    /**
     * @Route("/nos-produits", name="gestion_product", methods={"GET|POST"})
     */
    public function index()
    {

        $items = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findall();


        return $this->render('gestionproduct/index.allproduct.html.twig', [
                'items'=>$items
        ]);
    }


    /**
     * @Route("/nos-produits/supprimer/{id}", name="gestion_delete_product", methods={"GET|POST"})
     *
     */
    public function remove(Product $id)
    {
        // recuperation de l'entity manager
        $items = $this->getDoctrine()->getManager();
        $items->remove($id);
        $items->flush();

       return $this->redirectToRoute('gestion_product');

    }


    /**
     * @Route("/nos-produits/creation-produit", name="gestion_create_product", methods={"GET|POST"})
     */
    public function create(Request $request, SluggerInterface $slugger)

    {
        #Creation d'un nouvel produit vide
        $product = new Product();
        $product->setCreatedAt(new \DateTime());

        #remplacer par l'itulisateur actuel(ici, l'admin)
        $product->setUser($this->getUser());#l"utilisateur qui est connecté


        #CREATION DE FORMULAIRE

        $form = $this->createForm(ProductType::class, $product);


        #Permet a symfony de gérer les données saisies par l'utilisateur
        $form->handleRequest($request);

        #Si le formulaire est soumis et valide

        if($form->isSubmitted() && $form->isValid()){

            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();


            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid('', true).'.'.$image->guessExtension();


                try {
                    $image->move(
                        $this->getParameter('images_directories'),
                        $newFilename
                    );
                } catch (FileException $e) {
                   $this->addFlash('danger','une erreur est survenue durant le chargement de votre page');
                }


                $product->setImage($newFilename);
            }

            # Generation de l'alias à partir de l'article
            $product->setSlug(
                $slugger->slug(
                    $product->getName()
                )
                );

            # Insertion dans la base de données

            $em =$this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();


            return $this->redirectToRoute('home_boutique');
        }


        return $this->render('gestionproduct/index.html.twig', [
            'form'=> $form->createView()
        ]);

}
    /**
     *
     * @Route("/nos-produits/modification/{id}", name="gestion_modification_produit", methods={"GET|POST"})
     */
    public function modification(Request $request, Product $product, SluggerInterface $slugger){

        //Récupération du formulaire
        $form = $this->createForm(ProductType::class, $product);//Lien Objet géré par le formulaire -> Requête soumission du formulaire
        $form-> handleRequest($request);

        //si le formulaire à été soumis et est valide
        if($form->isSubmitted() && $form->isValid()){

            //enregistrement du produit dans la bdd
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();


            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid('', true).'.'.$image->guessExtension();


                try {
                    $image->move(
                        $this->getParameter('images_directories'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger','une erreur est survenue durant le chargement de votre page');
                }


                $product->setImage($newFilename);
            }

            # Generation de l'alias à partir de l'article
            $product->setSlug(
                $slugger->slug(
                    $product->getName()
                )
            );


            $em = $this->getDoctrine()->getManager();

            //inutile, l'objet provient de la BDD
            //$em->persist($produit);

            $em->flush();

            $this->addFlash('success',"Le produit à bien été modifié dans la base de donnée.");

        }

        //Génération du code HTML pour le formulaire créé
        $formView = $form->createView();

        //Affichage de la vue
        return $this->render('gestionproduct/index.html.twig', [

            'form'=>$formView
        ]);

    }



}
