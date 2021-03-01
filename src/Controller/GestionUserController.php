<?php

namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard")
 */

class GestionUserController extends AbstractController
{
    /**
     * @Route("/utilisateurs", name="gestion_user", methods={"GET|POST"})
     */
    public function index()
    {

        $items = $this->getDoctrine()
            ->getRepository(User::class)
            ->findall();


        return $this->render('gestionuser/index.html.twig', [
            'items'=>$items
        ]);
    }

    /**
     * @Route("utilisateurs/supprimer/{id}", name="suppression_user", methods={"GET|POST"})
     *
     */
    public function suppression(User $user)
    {

        // recuperation de l'entity manager
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', " l'utilisateur a été supprimé");

        return $this->redirectToRoute('gestion_user');

    }


}
