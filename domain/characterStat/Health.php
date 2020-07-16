<?php

declare(strict_types=1);

namespace battleGame\domain\characterStat;

use DomainException;

class Health extends AbstractCharacterStat
{
    public const MIN_ALLOWED_HEALTH = 1;

    /**
     * Lowers the health by a certain amount
     *
     * @param int $factor
     *
     * @return self
     */
    public function lowerHealthBy(int $factor) : self
    {
        if ($factor < 0) {
            throw new DomainException(
                'Could not lower health by the given factor. Invalid factor.');
        }

        $newHealthValue = $this->value - $factor;

        if ($newHealthValue < self::MIN_ALLOWED_HEALTH) {
            $newHealthValue = 0;
        }

        $self = new self();
        $self->value = $newHealthValue;
        return $self;
    }

    /**
     * {@inheritDoc}
     */
    protected function assertStatValueFitsGameMechanics(int $value) : void
    {
        if ($value < self::MIN_ALLOWED_HEALTH) {
            throw new DomainException(
                'Failed to assert that the health stat fits the game mechanics.');
        }
    }
}