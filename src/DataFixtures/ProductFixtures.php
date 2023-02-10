<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public const PRODUCTS = [
        [
            'vat'         => 0,
            'sku'         => 'sito-12',
            'priceHT'     => 112.23,
            'title'       => 'ROBINET A BOISSEAU SPHERIQUE LAITON FEMELLE/FEMELLE POIGNEE ROUGE PN25 - ASTORI',
            'description' => 'Corps en laiton CW617N, nickelé extérieur et brut intérieur.
                                Sphère en laiton chromé.
                                Passage intégral PN25.
                                Tige inéjectable.
                                Double étanchéité à la tige par 2 joints toriques en NBR.
                                Joints de sphère en PTFE.
                                Modèle F-F.
                                Poignée plate en acier traité Géomet® 321 de couleur rouge.
                                Utilisation :
                                Eau potable, chauffage, eau glacée (glycol < 50%).
                                Air comprimé 8 b. maxi du Ø 12x17 au Ø 50x60.
                                P. maxi : 25 b.
                                T° : -10° à +110°C.
                                Point fort : Fabrication italienne, garantie 5 ans.
                                Traçabilité : Date de fabrication marquée sur le corps.',
            'isActivated' => true
        ],
        [
            'vat'         => 1,
            'sku'         => 'SR23',
            'priceHT'     => 26.64,
            'title'       => 'RACCORD A SERRAGE EXTERIEUR LAITON EN TE POUR TUBE PE / A VISSER FEMELLE',
            'description' => 'Té F à serrage extérieur pour tube P.E. eau.
                                Corps en laiton.
                                Joint d\'étanchéité NBR.
                                Utilisation :
                                P. maxi : 16 b.
                                T° maxi : 40°C.',
            'isActivated' => false
        ],
        [
            'vat'         => 2,
            'sku'         => 'REGAREC',
            'priceHT'     => 70.40,
            'title'       => 'REGARD RECTANGULAIRE POLYPROPYLENE',
            'description' => 'Regard rectangulaire verrouillable.
                                Pour canalisation Ø 70 mm maxi.',
            'isActivated' => true
        ],
        [
            'vat'         => 2,
            'sku'         => 'RBA240',
            'priceHT'     => 140.69,
            'title'       => 'CONE ADDUCTION A BRIDES MOBILES PN16 ACS',
            'description' => 'Cône à brides mobiles.
                                Revêtement époxy.
                                Raccordement sur brides PN10/16.
                                (DN80 : 8 trous).
                                Utilisation :
                                P. maxi : 16 b.
                                T° : 0 à +60°C.',
            'isActivated' => true
        ],
        [
            'vat'         => 1,
            'sku'         => '2403',
            'priceHT'     => 240.24,
            'title'       => 'COLLIER DE REPARATION A TOLERANCE INOX 304L GEBO',
            'description' => 'Collier de réparation à tolérance en inox 304L (1.4301).
                                Modèle 2 tirants en inox.
                                Longueur 200 mm.
                                Manchette d\'étanchéisation en EPDM collée sur toute la surface.
                                Utilisation :
                                Pour rendre étanche des trous, des fissures et des zones poreuses dans des conduits d\'eau.
                                Adapté pour réparation définitive pour tube en acier, cuivre, inox, fonte et fibrociment et réparation temporaire pour tube en PE et PVC.
                                P. maxi : 16 b.
                                T° maxi : 90°C.',
            'isActivated' => false
        ],
        [
            'vat'         => 0,
            'sku'         => 'JNBR',
            'priceHT'     => 3.00,
            'title'       => 'JOINT POUR RACCORD SYMETRIQUE GUILLEMIN NBR',
            'description' => 'Joint NBR pour raccord symétrique guillemin.
                                Sur demande joint PTFE et EPDM.',
            'isActivated' => true
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PRODUCTS as $PRODUCT) {
            $product = new Product();

            $product
                ->setSku($PRODUCT['sku'])
                ->setTitle($PRODUCT['title'])
                ->setDescription($PRODUCT['description'])
                ->setPriceHT($PRODUCT['priceHT'])
                ->setIsActivated($PRODUCT['isActivated'])
            ;

            $product->setVat(
                $this->getReference('vat_' . $PRODUCT['vat'])
            );

            $manager->persist($product);

        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            VatFixtures::class
        ];
    }
}
