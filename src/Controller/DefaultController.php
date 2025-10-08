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

    /**
     * Event's categories page
     * ex. https://localhost:8000/category/1
     * ex. https://localhost:8000/category/2
     * @return Response
     */
    #[Route('/category/{id}', name: 'default_category', methods: ['GET'])]
    public function category($id, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);

        return $this->render('default/category.html.twig', ["category" => $category]);
    }

    /**
     * Event's detail page
     * ex. https://localhost:8000/music/dnb-dj-set-4564
     * ex. https://localhost:8000/{param:type}/{param:titre}_{param:id}
     * @return Response
     */
    #[Route('/{category}/{title}_{id}', name: 'default_event', methods: ['GET'])]
    public function event($category, $title, $id): Response
    {

        $event = [
            'img' => "img/placeholder/2013-10-13_02.28.27dssss.webp",
            'title' => "DnB & Jungle DJ Set",
            'description' => "Immerse yourself in a vibrant night dedicated to Drum and Bass and Jungle music, featuring renowned DJs and spectacular live performances. The event promises an electrifying atmosphere, enhanced by dynamic light shows and interactive animations that captivate and energize the crowd.

Discover chill-out spaces designed for relaxation and connection, where you can meet fellow electronic music enthusiasts and share your passion for innovative sounds. The setting encourages friendly exchanges and new encounters, making the experience both social and memorable.

Enjoy a festive environment filled with varied activities, ensuring there’s something for everyone throughout the evening. Whether you’re a devoted fan or simply curious, this event offers a unique blend of music, energy, and conviviality for an unforgettable night.",
            'date' => "2024-07-15",
            'time' => "21:00",
            "user_int" => 11,
            "user_registered" => 5,
            "isJoined" => true
        ];
        return $this->render('default/event.html.twig', [
            'event' => $event
        ]);
    }

}
