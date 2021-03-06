<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Form\CalendarType;
use App\Notification\ContactNotification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/calendar")
 */
class CalendarController extends AbstractController
{

    /**
     * @Route("/", name="calendar_index", methods={"GET"})
     */
    public function index(): Response
    {
        // $prestations = $this->getUser()->getPrestations();
        return $this->render('calendar/index.html.twig');
    }

    /**
     * @Route("/new", name="calendar_new", methods={"GET|POST"})
     * @param Request $request
     * @param ContactNotification $notification
     * @return Response
     */
    public function new(Request $request,  ContactNotification $notification): Response
    {
        $calendar = new Calendar();
        $calendar->setUser($this->getUser());
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($calendar);
            $entityManager->flush();
            $notification->sendResponse();
            return $this->redirectToRoute('calendar_index');
        }

        return $this->render('calendar/new.html.twig', [
            'calendar' => $calendar,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="calendar_show", methods={"GET"})
     */
    public function show(Calendar $calendar): Response
    {
        $user = $this->getUser();

        return $this->render('calendar/show.html.twig', [
            'calendar' => $calendar,
            'user'=>$user
        ]);
    }


    /**
     * @Route("/{id}/edit", name="calendar_edit", methods={"GET|POST"})
     */
    public function edit(Request $request, Calendar $calendar): Response
    {
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('calendar_index');
        }

        return $this->render('calendar/edit.html.twig', [
            'calendar' => $calendar,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="calendar_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Calendar $calendar): Response
    {
        if ($this->isTokenValid('delete' . $calendar->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($calendar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('calendar_index');
    }
}
