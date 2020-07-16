<?php

declare(strict_types=1);

namespace battleGame\presentation;

class CharacterStatsViewModel
{
    public int $health;

    public int $defence;

    public int $strength;

    public int $speed;

    public int $luck;

    public function __construct(int $health, int $defence, int $strength, int $speed, int $luck)
    {
        $this->health = $health;
        $this->defence = $defence;
        $this->strength = $strength;
        $this->speed = $speed;
        $this->luck = $luck;
    }
}