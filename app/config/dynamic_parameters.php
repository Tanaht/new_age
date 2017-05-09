<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 22/03/2017
 * Time: 22:00
 */


//@warning Never set Object as parameter, or at least serialize it but its very a bad idea
$container->setParameter('current_date.year', date('Y'));
$container->setParameter('current_date.month', date('m'));
$container->setParameter('current_date.day', date('d'));