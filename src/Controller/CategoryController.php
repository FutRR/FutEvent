<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    /**
     * List of all categories
     * Ex. https://localhost:8000/category
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
     * Create a new category
     * Ex. https://localhost:8000/category/new
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/category/new', name: 'category_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $category =$form->getData();

            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Category created successfully');

            return $this->redirectToRoute('category_show', ['id' => $category->getId()]);
        }

        return $this->render('category/new.html.twig', [
            'categoryForm' => $form,
        ]);
    }

    /**
     * Category event list
     * Ex. https://localhost:8000/category/1
     * Ex. https://localhost:8000/category/2
     * @param Category $category
     * @return Response
     */
    #[Route('/category/{id}', name: 'category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', ['category' => $category]);
    }
}
