<?php

namespace App\Controller;

use App\Entity\Calendar;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * @Route("/api/{id}/edit", name="api_event_edit", methods={"PUT"})
     * @return Response
     */
    #method PUT est une methode qui met un jour un enregistrement ou l'a créer s'il n'existe pas
    public function majEvent(?Calendar $calendar, Request $request): Response
    {
        # On recupere les données par fullcalendar
        $donnees = json_decode($request->getContent());

        if(
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->description) && !empty($donnees->description) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor) &&
            isset($donnees->borderColor) && !empty($donnees->borderColor) &&
            isset($donnees->TextColor) && !empty($donnees->TextColor)
        ){
            # les données sont completes

            # On initilise un code
            $code = 200;
            #On verifie si l'id existe
            if(!$calendar){
                #on instancie un rdv
                $calendar = new Calendar;

                #On change le code
                $code = 201;
            }

            //on hydrate l'objet avec les données
            $calendar->setTitle($donnees->title);
            $calendar->setDescription($donnees->description);
            $calendar->setStart(new DateTime($donnees->start));
            if($donnees->allDay){
                $calendar->setEnd(new DateTime($donnees->start));
            }else{
                $calendar->setEnd(new DateTime($donnees->end));
            }
            $calendar->setAllDay($donnees->allDay);
            $calendar->setBackgroundColor($donnees->backgroundColor);
            $calendar->setBorderColor($donnees->borderColor);
            $calendar->setTextColor($donnees->textColor);

            $em = $this->getDoctrine()->getManager();
            $em->persist($calendar);
            $em->flush();

            //on retoune le code (soit un created soit un succes)
            return new Response('Ok', $code);
        }else{
            #les donnees sont incompletes
            return new Response('Données incomplètes', 404);
        }
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}
