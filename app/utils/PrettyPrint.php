<?php declare(strict_types=1);
namespace app\utils;

class PrettyPrint
{
    static function array(array $array): void
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }
}
