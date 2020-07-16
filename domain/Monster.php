<?php
declare(strict_types=1);

namespace battleGame\domain;

class Monster extends AbstractCharacter
{
    /**
     * Disable the generation of new objects using the new keyword
     * This will force the client to use the more verbose static factory methods
     */
    private function __construct()
    {
    }

    /**
     * Creates a new hero with random stats
     *
     * @param int $healthMin
     * @param int $healthMax
     * @param int $strengthMin
     * @param int $strengthMax
     * @param int $defenceMin
     * @param int $defenceMax
     * @param int $speedMin
     * @param int $speedMax
     * @param int $luckMin
     * @param int $luckMax
     * @param IRandomNumberGenerator $randomNumberGenerator
     *
     * @return self
     */
    public static function createWithRandomStats(
        int $healthMin,
        int $healthMax,
        int $strengthMin,
        int $strengthMax,
        int $defenceMin,
        int $defenceMax,
        int $speedMin,
        int $speedMax,
        int $luckMin,
        int $luckMax,
        IRandomNumberGenerator $randomNumberGenerator) : self
    {
        $self = new self();

        $self->setRandomStats(
            $healthMin,
            $healthMax,
            $strengthMin,
            $strengthMax,
            $defenceMin,
            $defenceMax,
            $speedMin,
            $speedMax,
            $luckMin,
            $luckMax,
            $randomNumberGenerator);

        return $self;
    }
}
