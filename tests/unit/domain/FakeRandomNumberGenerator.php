<?php

declare(strict_types=1);

namespace battleGame\tests\unit\domain;

use battleGame\domain\IRandomNumberGenerator;

class FakeRandomNumberGenerator implements IRandomNumberGenerator
{
    private int $seed;

    public function __construct()
    {
        $this->seed = 0;
    }

    public function generateFromRange(int $min, int $max): int
    {
        if ($min === $max) {
            return $min;
        }

        if ($min + $this->seed <= $max) {
            $num = $min + $this->seed;
            $this->seed++;

            return $num;
        } else {
            $this->seed = 0;
            $num = $min + $this->seed;
            $this->seed++;

            return $num;
        }
    }
}