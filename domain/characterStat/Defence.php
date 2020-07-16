<?php

declare(strict_types=1);

namespace battleGame\domain\characterStat;

use DomainException;

class Defence extends AbstractCharacterStat
{
    public const MIN_ALLOWED_DEFENCE = 0;

    /**
     * {@inheritDoc}
     */
    protected function assertStatValueFitsGameMechanics(int $value) : void
    {
        if ($value < self::MIN_ALLOWED_DEFENCE) {
            throw new DomainException(
                'Failed to assert that the defence stat fits the game mechanics.');
        }
    }
}