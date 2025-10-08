<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        $categories = [
            ['name' => 'Music', 'image' => 'img/categories/music.jpg'],
            ['name' => 'Sports', 'image' => 'img/categories/sports.jpg'],
            ['name' => 'Arts', 'image' => 'img/categories/arts.jpg'],
            ['name' => 'Technology', 'image' => 'img/categories/technology.jpg'],
            ['name' => 'Health', 'image' => 'img/categories/health.jpg'],
            ['name' => 'Education', 'image' => 'img/categories/education.jpg'],
            ['name' => 'Travel', 'image' => 'img/categories/travel.jpg'],
            ['name' => 'Food & Drink', 'image' => 'img/categories/food_drink.jpg'],
        ];

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}
