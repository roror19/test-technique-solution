<?php

namespace App\Tests;

use App\DataFixtures\ProductFixtures;
use App\DataFixtures\VatFixtures;
use App\DTO\Cart;
use App\Entity\Product;
use App\Services\CartServices;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CartServicesTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?AbstractDatabaseTool $databaseTool;

    protected function setUp(): void
    {
        parent::setUp();

        $this->databaseTool  = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }

    #[NoReturn]
    public function testCalculateTTC(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $this->databaseTool->loadFixtures([
            VatFixtures::class,
            ProductFixtures::class
        ]);

        $product = $this->entityManager->getRepository(Product::class)
            ->findOneBy([]);

        $cartServices  = $container->get(CartServices::class);
        $methodeResult = $cartServices->calculateTTC($product);

        $this->assertEquals(
            134.68,
            $methodeResult,
            'Le résultat attendu de la valeur TTC doit être 134.68, avez-vous fait attention aux décimales ?'
        );
    }

    #[NoReturn]
    public function testCalculateTotal(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $this->databaseTool->loadFixtures([
            VatFixtures::class,
            ProductFixtures::class
        ]);

        $product = $this->entityManager->getRepository(Product::class)
            ->findOneBy([]);

        $cartServices  = $container->get(CartServices::class);
        $methodeResult = $cartServices->calculateTotal($product, 2);

        $this->assertEquals(
            269.36,
            $methodeResult,
            'Le résultat attendu de la valeur TTC doit être 269.36, avez-vous fait attention aux décimales ?'
        );
    }

    #[NoReturn]
    public function testCalculateFinalTotal(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $this->databaseTool->loadFixtures([
            VatFixtures::class,
            ProductFixtures::class
        ]);

        $product = $this->entityManager->getRepository(Product::class)
            ->findOneBy([]);

        $cart = new Cart();

        $cart->addProduct($product, 2);

        $cartServices  = $container->get(CartServices::class);
        $methodeResult = $cartServices->calculateFinalTotal($cart);

        $this->assertEquals(
            269.35,
            $methodeResult,
            'Le résultat attendu de la valeur TTC doit être 269.35, avez-vous fait attention aux décimales ?'
        );
    }
}