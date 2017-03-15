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
        //TODO: @antMu: tu voulais faire quoi ici ? (on utilise quel format ? "+xx x xx xx xx xx" | "xx xx xx xx xx" )
        //Test de la présence de l'indicateur
        //un string n'est pas un array (utiliser la fonction substr()

        if(substr($string,0,1)=='+'){
            return substr($string, 0,3)." (0)".substr($string, 3,1)." ".substr($string, 4,2)." ".substr($string, 6,2)." ".substr($string, 8,2)." ".substr($string, 10,2);
        }
        else{
            return substr($string, 0,2)." ".substr($string, 2,2)." ".substr($string, 4,2)." ".substr($string, 6,2)." ".substr($string, 8,2);
        }

        return $string;
    }

    public function getName()
    {
        return 'twig_extension';
    }
}