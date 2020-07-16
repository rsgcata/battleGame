<?php

declare(strict_types=1);

namespace battleGame\domain;

interface IRandomNumberGenerator
{
    /**
     * Generates a random number
     *
     * @param int $min
     * @param int $max
     *
     * @return int
     */
    public function generateFromRange(int $min, int $max) : int;
}