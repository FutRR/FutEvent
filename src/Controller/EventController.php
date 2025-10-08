<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EventController extends AbstractController
{
    /**
     * Event's detail page
     * ex. https://localhost:8000/music/dnb-dj-set-4564
     * ex. https://localhost:8000/{param:type}/{param:titre}_{param:id}
     * @param Event $event
     * @return Response
     */
    #[Route('/event/{name}_{id}', name: 'event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', ['event' => $event]);
    }
}
