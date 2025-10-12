<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class EventController extends AbstractController
{

    #[Route('/event/new', name: 'event_new', methods: ['GET', 'POST'])]
    #[Route('/event/{id}/edit', name: 'event_edit', methods: ['GET', 'POST'])]
    #[isGranted('ROLE_USER')]
    public function new(Event $event = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        $isNewEvent = !$event;

        $message = $isNewEvent ? 'New event created' : 'Event updated';

        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();

            $image = $form['image']->getData();
            $image->move()

            $entityManager->persist($event);
            $entityManager->flush();
            $this->addFlash('success', "$message successfully");
            return $this->redirectToRoute('event_show', ['id' => $event->getId()]);
        }

        return $this->render('event/new.html.twig', [
            'eventForm' => $form,
            'edit' => !$isNewEvent,
        ]);
    }


    /**
     * Event's detail page
     * ex. https://localhost:8000/music/dnb-dj-set-4564
     * ex. https://localhost:8000/{param:type}/{param:titre}_{param:id}
     * @param Event $event
     * @return Response
     */
    #[Route('/event/{title}_{id}', name: 'event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', ['event' => $event]);
    }
}
