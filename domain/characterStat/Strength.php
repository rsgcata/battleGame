<?php

declare(strict_types=1);

namespace battleGame\domain\characterStat;

use DomainException;

class Strength extends AbstractCharacterStat
{
    const MIN_ALLOWED_STRENGTH = 1;

    /**
     * {@inheritDoc}
     */
    protected function assertStatValueFitsGameMechanics(int $value) : void
    {
        if ($value < self::MIN_ALLOWED_STRENGTH) {
            throw new DomainException(
                'Failed to assert that the strength stat fits the game mechanics.');
        }
    }
}