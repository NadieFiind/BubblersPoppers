<?php declare(strict_types=1);
require "vendor/autoload.php";
use app\models\Universe;
use app\utils\PrettyPrint;

const universe = new Universe();
$b1 = universe->makeBubble();
$b2 = universe->makeBubble();
$b3 = universe->makeBubble();

PrettyPrint::array(universe->bubbles);
