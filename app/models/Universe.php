<?php declare(strict_types=1);
namespace app\models;

class Universe
{
    public array $bubbles = [];
    public int $existed_bubbles = 0;

    function make_bubble(): Bubble
    {
        $id = ++$this->existed_bubbles;
        $bubble = new Bubble($this, $id);
        $this->bubbles[$id] = $bubble;
        return $bubble;
    }

    function pop_bubble(Bubble $bubble): void
    {
        unset($this->bubbles[$bubble->id]);
    }
}
