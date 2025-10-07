<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DefaultController extends AbstractController
{

    #[Route('/', name: 'default_home', methods: ['GET'])]
    public function home(): Response
    {
        return $this->render('default/home.html.twig');
    }

    /**
     * Event's categories page
     * ex. https://localhost:8000/category/sport
     * ex. https://localhost:8000/category/music
     * @return Response
     */
    #[Route('/category/{type}', name: 'default_category', methods: ['GET'])]
    public function category($type): Response
    {
        $events = [
                [
                    'img' => "img/placeholder/2013-10-13_02.28.27dssss.webp",
                    'title' => "DnB & Jungle DJ Set",
                    'description' => "Dive into an electrifying night dedicated to Drum and Bass and Jungle rhythms. Top DJs from the scene will make you vibrate with exclusive sets, live performances, and a high-energy atmosphere. Enjoy an immersive experience with light shows, interactive animations, and chill-out spaces to meet other electronic music enthusiasts.",
                    'date' => "2024-07-15",
                    'time' => "21:00",
                    "user_int" => 11,
                    "user_registered" => 5,
                    "isJoined" => true
                ],
                [
                    'img' => "img/placeholder/band.webp",
                    'title' => "Rock Concert",
                    'description' => "Experience an unforgettable evening of rock with internationally renowned bands and local talents. Expect wild guitar solos, energetic stage performances, and a festive atmosphere. Join us to share the passion for rock, discover new artists, and enjoy special activities throughout the night.",
                    'date' => "2024-08-20",
                    'time' => "20:00",
                    "user_int" => 50,
                    "user_registered" => 30,
                    "isJoined" => false
                ],
                [
                    'img' => "img/placeholder/554c21bce1d4732693654606988690b2.webp",
                    'title' => "Football Match",
                    'description' => "Attend a thrilling football match between two top-level teams. Feel the intensity of the game, spectacular actions, and the excitement of the fans. Enjoy pre- and post-match entertainment, food stands, and activities for the whole family. A sporting event not to be missed for all football lovers.",
                    'date' => "2024-09-10",
                    'time' => "18:00",
                    "user_int" => 200,
                    "user_registered" => 150,
                    "isJoined" => false
                ],
                [
                    'img' => "img/placeholder/match-nba-new-york-knicks-1024x503.webp",
                    'title' => "Basketball Game",
                    'description' => "Come cheer for the country's best players during an exceptional basketball game. Enjoy the dynamic atmosphere, spectacular dunks, and on-court entertainment. Contests, meet-and-greets with players, and dedicated fan zones will make this evening memorable for all basketball enthusiasts.",
                    'date' => "2024-10-05",
                    'time' => "19:30",
                    "user_int" => 120,
                    "user_registered" => 80,
                    "isJoined" => true
                ],
                [
                    'img' => "img/placeholder/orchestral-performance-with-violinists-focus_1286780-4844.webp",
                    'title' => "Classical Music Concert",
                    'description' => "Savor an evening of classical music with a renowned orchestra and talented soloists. Let yourself be transported by masterful works, moving interpretations, and exceptional acoustics. A refined event, perfect for music lovers and those wishing to discover the beauty of orchestral music.",
                    'date' => "2024-11-12",
                    'time' => "19:00",
                    "user_int" => 70,
                    "user_registered" => 40,
                    "isJoined" => false
                ],
            ];
        return $this->render('default/category.html.twig', ['type' => $type, 'events' => $events]);
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
