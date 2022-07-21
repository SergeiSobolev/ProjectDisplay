<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\SearchType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly ProductRepository $productRepository,
    ) {}

    public function index(Request $request, int $page = 1): Response
    {

        $categoriesName = $this->categoryRepository->getNameAllCategory();

        $formSearch = $this->createForm(SearchType::class, options: ['choices' => $categoriesName]);

        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {

            $data = $formSearch->getData();

            $products = $this->productRepository->searchByQuery($data);

            return $this->render('index/index.html.twig', [
                'products' => $products,
                'formSearch' => $formSearch->createView()
            ]);
        }
        $limit = 10;

        $products = $this->productRepository->findProductsPaginated($limit, $page);

        $paginator = new Paginator(
            $products->count(),
            $page,
            $limit,
            6
        );

        return $this->render('index/index.html.twig', [
            'products' => $products,
            'paginator_s' => $paginator,
            'formSearch' => $formSearch->createView()
        ]);
    }

    public function showCategory(Category $category): Response
    {
        return $this->render('categories/index.html.twig', [
            'products' => $category->getProducts(),
        ]);
    }

    public function showCategories(): Response
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('index/left-sidebar_block.html.twig', [
            'categories' => $categories
        ]);
    }
}