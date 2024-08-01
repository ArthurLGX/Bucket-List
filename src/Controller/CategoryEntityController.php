<?php

namespace App\Controller;

use App\Repository\CategoryEntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category', name: 'app_category_')]
class CategoryEntityController extends AbstractController
{

    #[Route('/entity', name: 'entity')]
    public function index(CategoryEntityRepository $categoryEntityRepository): Response
    {
        $categories = $categoryEntityRepository->findAll();

        return $this->render('category/categories.html.twig', [
            'controller_name' => 'CategoryEntityController',
            'categories' => $categories
        ]);
    }
}
