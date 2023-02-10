<?php

namespace App\Interfaces;

use App\DTO\Cart;
use App\Entity\Product;

interface CartServicesInterface
{
    public function calculateTTC(Product $product): void;

    public function calculateTotal(Product $product, int $quantity): void;

    public function calculateFinalTotal(Cart $cart): void;
}