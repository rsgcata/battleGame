<?php

declare(strict_types=1);

namespace battleGame\domain;

class DefaultMonsterStats
{
    /**
     * Min and max stats range
     */
    public const MIN_HEALTH = 60;
    public const MAX_HEALTH = 90;
    public const MIN_STRENGTH = 60;
    public const MAX_STRENGTH = 90;
    public const MIN_DEFENCE = 40;
    public const MAX_DEFENCE = 60;
    public const MIN_SPEED = 40;
    public const MAX_SPEED = 60;
    public const MIN_LUCK = 25;
    public const MAX_LUCK = 40;
}