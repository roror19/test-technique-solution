<?php

namespace App\Interfaces;

use App\DTO\Cart;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface CartServicesInterface
{
    public function calculateTTC(Product $product): float;

    public function calculateTotal(Product $product, int $quantity): float;

    public function calculateFinalTotal(Cart $cart): float;

    public function countProducts(SessionInterface $session, Request $request): int;

    public function updateCart(SessionInterface $session, Request $request): void;

    public function getProducts(SessionInterface $session): Cart;

    public function removeProductFromCart(SessionInterface $session, Product $product): void;
}