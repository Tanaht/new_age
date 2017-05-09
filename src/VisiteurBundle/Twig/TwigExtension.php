<?php
namespace VisiteurBundle\Twig;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\RegexValidator;
use Symfony\Component\Validator\Validation;
use VisiteurBundle\Entity\Notification;

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
            new \Twig_SimpleFilter('filter', [$this, 'notificationFilter'])
        ];
    }

    public function notificationFilter(array $collection, array $filters) {
        if(count($collection) == 0)
            return $collection;

        /** @var Notification $designated */
        $designated = $collection[0];

        $accessor = PropertyAccess::createPropertyAccessor();
        $validator = Validation::createValidator();

        $resolver = new OptionsResolver();
        foreach ($filters as $filterKey => $filterValue) {
            $resolver->setRequired($filterKey);
            $resolver->setAllowedValues($filterKey, function ($value) use ($filterKey, $validator, $designated, $accessor) {
                if (!$accessor->isReadable($designated, $filterKey))
                    return false;

                if(is_object($value)) {
                    if(!is_object($accessor->getValue($designated, $filterKey)))
                        return false;
                    elseif(get_class($accessor->getValue($designated, $filterKey)) != get_class($value))
                        return false;
                }
                return true;
            });
        }

        $resolver->resolve($filters);

        $filteredNotifications = new ArrayCollection($collection);

        return $filteredNotifications->filter(function($value) use($filters, $validator, $accessor) {
            foreach ($filters as $filterKey => $filterValue) {
                $valueToFilter = $accessor->getValue($value, $filterKey);
                if(is_object($valueToFilter)) {
                    if($valueToFilter !== $value)
                        return false;
                }
                else {
                    $violations = $validator->validate($valueToFilter, [
                        new Regex(['pattern' => "/$filterValue/"])
                    ]);

                    if(count($violations) !== 0)
                        return false;
                }
            }

            return true;

        });
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