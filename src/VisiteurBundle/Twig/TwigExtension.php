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
        //Test de la présence de l'indicateur
        if($string[0]=='+'){
            return substr($string, 0,3)." ".substr($string, 4,2)." ".substr($string, 6,2)." ".substr($string, 8,2)." ".substr($string, 10,2);
        }
        else{
            return substr($string, 0,2)." ".substr($string, 2,2)." ".substr($string, 4,2)." ".substr($string, 6,2);
        }
    }

    public function getName()
    {
        return 'twig_extension';
    }
}