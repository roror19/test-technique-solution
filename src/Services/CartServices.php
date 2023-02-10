<?php

namespace App\Services;

use App\DTO\Cart;
use App\Entity\Product;
use App\Interfaces\CartServicesInterface;

class CartServices implements CartServicesInterface
{
    public function calculateTTC(Product $product): void
    {
        // TODO: Implement calculateTTC() method.
    }

    public function calculateTotal(Product $product, int $quantity): void
    {
        // TODO: Implement calculateTotal() method.
    }

    public function calculateFinalTotal(Cart $cart): void
    {
        // TODO: Implement calculateFinalTotal() method.
    }
}