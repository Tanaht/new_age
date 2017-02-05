<?php
namespace VisiteurBundle\Twig;

/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 01/02/2017
 * Time: 18:42
 */
class TwigExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('phone_number_format', [$this, 'phoneNumberFormat']),
        ];
    }

    public function phoneNumberFormat($string)
    {
        $country = substr($string, 0, strlen($string) - 9);
        $type = substr($string, strlen($string) - 9, 1);
        $digits = chunk_split(substr($string, strlen($string) - 8), 2, ' ');

        return $country . ' ' . $type . ' ' . $digits;
    }

    public function getName()
    {
        return 'twig_extension';
    }
}