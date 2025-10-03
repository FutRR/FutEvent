<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{

    #[Route('/', name: 'default_home', methods: ['GET'])]
    public function home()
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
    public function category($type)
    {
        return $this->render('default/category.html.twig', ['type' => $type]);
    }

    /**
     * Event's detail page
     * ex. https://localhost:8000/music/dnb-dj-set-4564
     * ex. https://localhost:8000/{param:type}/{param:titre}_{param:id}
     * @return Response
     */
    #[Route('/{category}/{title}_{id}', name: 'default_event', methods: ['GET'])]
    public function event($category, $title, $id)
    {
        return new Response("
            <h1>Catégorie : $category
            <br> Titre : $title
            <br> ID : $id
            </h1>
        ");
    }

}
