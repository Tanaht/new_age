<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 31/03/2017
 * Time: 21:08
 */

namespace ToolsBundle\Services\ExcelMappingParser;


use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class MappingParser
{
    public static function parse($filepath) {
        try {
            $value = Yaml::parse(file_get_contents($filepath));
            dump($value);
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }
    }
}