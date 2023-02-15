<?php

namespace App\Controller;

use App\DTO\Cart;
use App\Entity\Product;
use App\Events\CartEvent;
use App\Interfaces\CartServicesInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/cart', name: 'cart_')]
class CartController extends AbstractController
{
    public function __construct(protected EntityManagerInterface   $entityManager,
                                protected EventDispatcherInterface $dispatcher,
                                protected CartServicesInterface    $cartServices
    ) { }

    #[Route(path: '', name: 'index')]
    public function index(Request $request): Response
    {
        /**
         * Récupérer les produits en session
         */
        $cart = $this->cartServices->getProducts($request->getSession());

        $event = new CartEvent($cart, $this->cartServices);

        $this->dispatcher->dispatch($event, CartEvent::NAME);

        return $this->render('products/cart.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route(path: '/clean', name: 'clean')]
    public function clean(Request $request): RedirectResponse
    {
        $session = $request->getSession();

        $session->clear();

        return $this->redirectToRoute('cart_index');
    }
}