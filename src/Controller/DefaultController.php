<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DefaultController extends AbstractController
{

    #[Route('/', name: 'default_home', methods: ['GET'])]
    public function home(EventRepository $eventRepository): Response
    {
        # Récupération des 2 prochains événements
        $events = $eventRepository->findBy([], ['datetime_start' => 'ASC'], 2);

        return $this->render('default/home.html.twig', [
            'events' => $events
        ]);
    }
}
