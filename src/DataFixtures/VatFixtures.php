<?php

namespace App\DataFixtures;

use App\Entity\Vat;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class VatFixtures extends Fixture
{
    public const VATS = [
        20.00, 10.00, 5.50
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::VATS as $index => $VAT) {
            $vat = new Vat();

            $vat->setAmount($VAT);

            $this->setReference('vat_' . $index, $vat);

            $manager->persist($vat);
        }

        $manager->flush();
    }
}
