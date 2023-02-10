<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TwigExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('priceTTC', [$this, 'formatPriceTTC']),
        ];
    }

    public function formatPriceTTC(float $priceHT, float $tvaAmount): float
    {

    }
}