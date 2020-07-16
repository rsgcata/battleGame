<?php

declare(strict_types=1);

namespace battleGame\domain\characterStat;

use DomainException;

class Luck extends AbstractCharacterStat
{
    const MIN_ALLOWED_LUCK = 0;

    const MAX_ALLOWED_LUCK = 100;

    /**
     * {@inheritDoc}
     */
    protected function assertStatValueFitsGameMechanics(int $value) : void
    {
        if ($value < self::MIN_ALLOWED_LUCK || $value > self::MAX_ALLOWED_LUCK) {
            throw new DomainException(
                'Failed to assert that the luck stat fits the game mechanics.');
        }
    }
}