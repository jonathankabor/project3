<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('pluralize', [$this, 'pluralize']),
        ];
    }

    public function pluralize( int $count, string $singular, ?string $plural = null): string
    {
        $plural = $plural ?? $singular . 's';
        $results = $count == 1? $singular: $plural;

        return "$count $results";
    }
}
