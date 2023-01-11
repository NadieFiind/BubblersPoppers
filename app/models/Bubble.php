<?php declare(strict_types=1);
namespace app\models;

class Bubble
{
    function __construct(public Universe $universe, public int $id)
    {
    }

    function pop(): void
    {
        $this->universe->pop_bubble($this);
    }
}
