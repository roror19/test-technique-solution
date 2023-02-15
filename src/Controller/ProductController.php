<?php

namespace App\Controller;

use App\Entity\Product;
use App\Interfaces\CartServicesInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/products', name: 'product_')]
class ProductController extends AbstractController
{
    public function __construct(protected EntityManagerInterface $entityManager,
                                protected CartServicesInterface  $cartServices
    ) { }

    #[Route(path: '', name: 'index')]
    public function index(): Response
    {
        $products = $this->entityManager->getRepository(Product::class)
            ->findActivatedproducts();

        return $this->render('products/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route(path: '/add', name: 'add')]
    public function addToCart(Request $request): JsonResponse
    {
        $session = $request->getSession();

        $quantity = $this->cartServices->countProducts($session, $request);

        return new JsonResponse($quantity, 200);
    }

    #[Route(path: '/reload', name: 'reload')]
    public function reloadCart(Request $request): JsonResponse
    {
        $session = $request->getSession();

        $this->cartServices->updateCart($session, $request);

        return new JsonResponse('success', 200);
    }

    #[Route(path: '/remove/{id}', name: 'remove')]
    public function removeProductFromCart($id, Request $request): RedirectResponse
    {
        $session = $request->getSession();

        if ($request->get('id')) {
            $product = $this->entityManager->getRepository(Product::class)
                ->find($request->get('id'));

            if ($product) {
                $this->cartServices->removeProductFromCart($session, $product);
            }
        }

        return $this->redirectToRoute('cart_index');
    }

    #[Route(path: '/count', name: 'count')]
    public function count(Request $request): JsonResponse
    {
        $session = $request->getSession();

        $quantity = $this->cartServices->countProducts($session, $request);

        return new JsonResponse($quantity, 200);
    }
}