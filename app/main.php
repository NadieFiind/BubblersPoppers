<?php declare(strict_types=1);
require "vendor/autoload.php";
use app\models\Universe;
use app\utils\PrettyPrint;

const universe = new Universe();
$b1 = universe->make_bubble();
$b2 = universe->make_bubble();
$b3 = universe->make_bubble();

PrettyPrint::array(universe->bubbles);
