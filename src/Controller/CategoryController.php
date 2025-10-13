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
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    #[Route('/category/new', name: 'category_new', methods: ['GET', 'POST'])]
    #[Route('/category/{id}/edit', name: 'category_edit', methods: ['GET', 'POST'])]
    #[isGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager, Category $category = null): Response
    {
        $isNewCategory = !$category;

        $message = $isNewCategory ? 'New category created' : 'Category updated';

        if (!$category){
            $category = new Category();
        }
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();

            $entityManager->persist($category);
            $entityManager->flush();
            flash()->success($message . ' successfully!');

            return $this->redirectToRoute('category_show', ['id' => $category->getId()]);
        }

        return $this->render('category/new.html.twig', [
            'categoryForm' => $form,
            'edit' => !$isNewCategory,
        ]);
    }

    #[Route('/category/{id}/delete', name: 'category_delete', methods: ['POST', 'DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            flash()->error('Invalid CSRF token');
            return $this->redirectToRoute('category_list');
        }

        // Check if category has associated events
        if ($category->getEvents()->count() > 0) {
            flash()->error('Cannot delete category with associated events. Please delete or reassign the events first.');
            return $this->redirectToRoute('category_show', ['id' => $category->getId()]);
        }

        try {
            $entityManager->remove($category);
            $entityManager->flush();
            flash()->success('Category deleted successfully');
            return $this->redirectToRoute('category_list');
        } catch (\Exception $e) {
            flash()->error('An error occurred while deleting the category');
        }

        return $this->redirectToRoute('category_list');
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
