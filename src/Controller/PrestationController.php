<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrestationController extends AbstractController
{
    /**
     * http://localhost:8000/service
     * @Route("/service", name="home_prestation" , methods={"GET"})
     */
    public function index():Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBySlug('service')->getProducts();
        return $this->render('prestation/index.html.twig',[
            'products' => $products
        ]);
    }

    /**
     * @Route("/service/{id}", name="boutique_service" , methods={"GET"})
     */
    public function serviceDetail($id):Response
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findOneById($id);

        if(!$product){
            return $this->redirectToRoute('home_prestation');
        }

        return $this->render('prestation/serviceDetail.html.twig',[
            'product' => $product
        ]);
    }

    /**
     * @Route("/indexCalendar", name="service_calendar" , methods={"GET"})
     */
    public function calendar(CalendarRepository $calendar):Response
    {
        $events = $calendar->findAll();

        $rdvs=[];

        foreach($events as $event){
            $rdvs[] = [
                'id'=>$event->getId(),
                'title'=>$event->getProduct()->getName(),
                'start'=>$event->getStart()->format('Y-m-d H:i:s'),
//               'end'=>$event->getEnd()->format('Y-m-d H:i:s'),
//               'description'=>$event->getDescription(),
//               'backgroundColor'=>$event->getBackgroundColor(),
//               'borderColor'=>$event->getBorderColor(),
//               'textColor'=>$event->getTextColor(),
//               'allDay'=>$event->getAllDay()
            ];
        }
        $data = json_encode($rdvs);
        return $this->render('prestation/serviceCalendar.html.twig', compact('data'));
    }

}
