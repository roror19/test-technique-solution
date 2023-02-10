<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/products', name: 'product_')]
class ProductController extends AbstractController
{
    public function __construct(protected EntityManagerInterface $entityManager) { }

    #[Route(path: '', name: 'index')]
    public function index(): Response
    {
        $products = $this->entityManager->getRepository(Product::class)
            ->findAll();

        return $this->render('products/index.html.twig', [
            'products' => $products
        ]);
    }
}