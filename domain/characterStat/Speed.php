<?php

declare(strict_types=1);

namespace battleGame\domain\characterStat;

use DomainException;

class Speed extends AbstractCharacterStat
{
    const MIN_ALLOWED_SPEED = 1;

    /**
     * {@inheritDoc}
     */
    protected function assertStatValueFitsGameMechanics(int $value): void
    {
        if ($value < self::MIN_ALLOWED_SPEED) {
            throw new DomainException(
                'Failed to assert that the speed stat fits the game mechanics.');
        }
    }
}