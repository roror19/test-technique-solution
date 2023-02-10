<?php

namespace App\Events;

use App\DTO\Cart;
use App\Interfaces\CartServicesInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class CartEvent extends Event
{
    public const NAME = 'negocian.cart.dispatcher';

    public function __construct(protected Cart $cart,
                                protected ?CartServicesInterface $cartServices
    ) {
        foreach ($this->cart->products as $product) {

            $this->cartServices->calculateTTC($product['product']);
            $this->cartServices->calculateTotal($product['product'], $product['quantity']);
        }

        $this->cartServices->calculateFinalTotal($this->cart);
    }
}