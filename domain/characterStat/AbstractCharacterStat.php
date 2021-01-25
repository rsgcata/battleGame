<?php

declare(strict_types=1);

namespace battleGame\domain\characterStat;

use battleGame\domain\IRandomNumberGenerator;
use DomainException;

abstract class AbstractCharacterStat
{
    /**
     * The value of the stat
     */
    protected int $value;

    /**
     * Disable the generation of new objects using the new keyword
     * This will force the client to use the more verbose static factory methods
     */
    final protected function __construct()
    {
    }

    /**
     * Creates a new stat random object based on the min value and max value for the stat
     *
     * @param int $minValue
     * @param int $maxValue
     * @param IRandomNumberGenerator $randomNumberGenerator
     *
     * @return static
     * @throws DomainException If min value and max value do not fit game mechanics
     */
    final public static function createRandomStatFromRange(
        int $minValue,
        int $maxValue,
        IRandomNumberGenerator $randomNumberGenerator)
    {
        if ($minValue > $maxValue) {
            throw new DomainException(
                'Could not create new random character stat from range.'
                . ' The min value should be less or equal to the max value.');
        }

        $self = new static();
        $self->assertStatValueFitsGameMechanics($minValue);
        $self->assertStatValueFitsGameMechanics($maxValue);

        $self->value = $randomNumberGenerator->generateFromRange($minValue, $maxValue);

        return $self;
    }

    /**
     * Assert that the value is fits the game mechanics based on the stat's type
     *
     * @param int $value
     *
     * @return void
     * @throws DomainException If value does not fit the game mechanics
     */
    protected abstract function assertStatValueFitsGameMechanics(int $value): void;

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}