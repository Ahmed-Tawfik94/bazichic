<?php
namespace App\Helpers;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PluralizeExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('pluralize', [$this, 'pluralize']),
        ];
    }

    /**
     * Pluralizes a word based on the count.
     *
     * @param int $count
     * @param string $singular
     * @param string $plural
     * @return string
     */
    public function pluralize(int $count, string $singular, string $plural): string
    {
        return $count === 1 ? $count .' '.$singular : $count.' '. $plural;
    }
}
