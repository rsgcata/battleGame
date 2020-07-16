<?php

declare(strict_types=1);

namespace battleGame\infrastructure\domain;

use battleGame\domain\IRandomNumberGenerator;

class RandomNumberGenerator implements IRandomNumberGenerator
{
    /**
     * {@inheritDoc}
     */
    public function generateFromRange(int $min, int $max): int
    {
        return random_int($min, $max);
    }
}