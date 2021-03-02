<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     *
     */

    public function index()
    {
        return $this->render('home/index.html.twig');
    }


    /**
     * @Route("/galerie", name="home_galerie", methods={"GET"})
     */

    public function galerie(): Response
    {
        return $this->render('home/galerie.html.twig');
    }

    /**
     * @Route("/bookingPolicy.html.twig", name="home_bookingPolicy", methods={"GET"})
     */

    public function bookingPolicy(): Response
    {
        return $this->render('home/bookingPolicy.html.twig');
    }

    /**
     * @Route("/termsOfService", name="home_termsOfService", methods={"GET"})
     */

    public function termsOfService(): Response
    {
        return $this->render('home/termsOfService.html.twig');
    }
    /**
     * @Route("/privacyPolicy", name="home_privacyPolicy", methods={"GET"})
     */

    public function privacyPolicy(): Response
    {
        return $this->render('home/privacyPolicy.html.twig');
    }
}
