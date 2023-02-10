<?php

namespace App\DTO;

use App\Entity\Product;

class Cart
{
    public array $products = [];

    public function addProduct(Product $product, int $quantity = 1): self
    {
        if (!isset($this->products[$product->getId()])) {

            $this->products[$product->getId()] = [
                'product'  => $product,
                'quantity' => $quantity
            ];

        } else {
            $this->products[$product->getId()]['quantity'] += $quantity;
        }

        return $this;
    }
}