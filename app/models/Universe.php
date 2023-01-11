<?php declare(strict_types=1);
namespace app\models;

class Universe
{
    public array $bubbles = [];
    public int $existedBubbles = 0;

    function makeBubble(): Bubble
    {
        $id = ++$this->existedBubbles;
        $bubble = new Bubble($this, $id);
        $this->bubbles[$id] = $bubble;
        return $bubble;
    }

    function popBubble(Bubble $bubble): void
    {
        unset($this->bubbles[$bubble->id]);
    }
}
