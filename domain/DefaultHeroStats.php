<?php

declare(strict_types=1);

namespace battleGame\domain;

class DefaultHeroStats
{
    /**
     * Min and max stats range
     */
    public const MIN_HEALTH = 70;
    public const MAX_HEALTH = 100;
    public const MIN_STRENGTH = 70;
    public const MAX_STRENGTH = 80;
    public const MIN_DEFENCE = 45;
    public const MAX_DEFENCE = 55;
    public const MIN_SPEED = 40;
    public const MAX_SPEED = 50;
    public const MIN_LUCK = 10;
    public const MAX_LUCK = 30;

    /**
     * Skill occurrence chances
     */
    public const RAPID_STRIKE_CHANCE = 0.1;
    public const MAGIC_SHIELD_CHANCE = 0.2;
}