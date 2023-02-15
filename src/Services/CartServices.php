<?php

namespace App\Services;

use App\DTO\Cart;
use App\Entity\Product;
use App\Interfaces\CartServicesInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartServices implements CartServicesInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly RequestStack $requestStack
    ) { }

    public function calculateTTC(Product $product): float
    {
        $ht  = $product->getPriceHT();
        $vat = $product->getVat()->getAmount();

        $ttc = $ht + ($ht * $vat / 100);

        $ttc = number_format((float)$ttc, 2, '.', '');

        $request = $this->requestStack->getMainRequest();

        if ($request) {
            $newData = $request->getSession()->get('product_' . $product->getId());

            $newData['ttc'] = $ttc;

            $request->getSession()->set('product_' . $product->getId(), $newData);
        }

        return $ttc;
    }

    public function calculateTotal(Product $product, int $quantity): float
    {
        $ht  = $product->getPriceHT();
        $vat = $product->getVat()->getAmount();

        $ttc = $ht + ($ht * $vat / 100);

        $ttc = number_format((float)$ttc, 2, '.', '');

        $request = $this->requestStack->getMainRequest();

        if ($request) {
            $session = $request->getSession();
            $newData = $session->get('product_' . $product->getId());

            $newData['totalTTC'] = number_format((float)($ttc * $newData['quantity']), 2, '.', '');

            $session->set('product_' . $product->getId(), $newData);

            return $newData['totalTTC'];
        }

        if ($quantity) {
            $ttc = number_format($ttc * $quantity, 2, '.', '');
        }

        return $ttc;
    }

    public function calculateFinalTotal(Cart $cart): float
    {
        $total = 0;

        $request = $this->requestStack->getMainRequest();

        if ($request) {
            $session = $request->getSession();

            foreach ($session->all() as $index => $product) {
                if (isset($product['totalTTC']) && str_starts_with($index, 'product_')) {
                    $total += $product['totalTTC'];
                }
            }

            $session->set('total', $total);

            return $total;
        }

        foreach ($cart->products as $product) {

            $ht  = $product['product']->getPriceHT();
            $vat = $product['product']->getVat()->getAmount();

            $ttc = $ht + ($ht * $vat / 100);

            $ttc = number_format($ttc * $product['quantity'], 2, '.', '');

            $total += (float)$ttc;
        }

        return $total;
    }

    public function countProducts(SessionInterface $session, Request $request): int
    {
        if (!$session->isStarted()) {
            $session->start();
        }

        $productId = $request->get('productId');

        if (!$session->get('product_' . $productId)) {

            $session->set('product_' . $productId, [
                'productId' => $productId,
                'quantity'  => $request->get('quantity')
            ]);

        } else {

            $session->set('product_' . $productId, [
                'productId' => $productId,
                'quantity'  => $session->get('product_' . $productId)['quantity'] + $request->get('quantity')
            ]);
        }

        $quantity = 0;
        foreach ($session->all() as $index => $product) {
            if (str_starts_with($index, 'product_')) {
                $quantity += $product['quantity'];
            }
        }

        return $quantity;
    }

    public function updateCart(SessionInterface $session, Request $request): void
    {
        $productId = $request->get('productId');

        if ((int)$request->get('quantity') > 0) {
            $session->set('product_' . $productId, [
                'productId' => $productId,
                'quantity'  => $request->get('quantity')
            ]);
        } else {
            $session->remove('product_' . $productId);
        }
    }

    public function getProducts(SessionInterface $session): Cart
    {
        $cart = new Cart();

        foreach ($session->all() as $index => $product) {
            if (isset($product['productId']) && str_starts_with($index, 'product_')) {

                $productForCart = $this->entityManager->getRepository(Product::class)
                    ->find($product['productId']);

                $quantity = $product['quantity'];

                $cart->addProduct($productForCart, $quantity);
            }
        }

        return $cart;
    }

    public function removeProductFromCart(SessionInterface $session, Product $product): void
    {
        $session->remove('product_' . $product->getId());
    }
}