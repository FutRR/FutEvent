<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    /**
     * List of all categories
     * ex. https://localhost:8000/category
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    #[Route('/category', name: 'category_list')]
    public function list(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Event's categories page
     * ex. https://localhost:8000/category/1
     * ex. https://localhost:8000/category/2
     * @param Category $category
     * @return Response
     */
    #[Route('/category/{id}', name: 'category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', ['category' => $category]);
    }
}
