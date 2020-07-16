<?php

declare(strict_types=1);

namespace battleGame\domain;

use battleGame\domain\characterStat\Defence;
use battleGame\domain\characterStat\Health;
use battleGame\domain\characterStat\Luck;
use battleGame\domain\characterStat\Speed;
use battleGame\domain\characterStat\Strength;

abstract class AbstractCharacter
{
    /**
     * The health of the character
     */
    protected Health $health;

    /**
     * The strength of the character
     */
    protected Strength $strength;

    /**
     * The strength of the character
     */
    protected Defence $defence;

    /**
     * The attack speed of the character
     */
    protected Speed $speed;

    /**
     * The luck of the character
     */
    protected Luck $luck;

    /**
     * Set random stats for the character based on min and max range
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
     * @return void
     */
    protected function setRandomStats(
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
        IRandomNumberGenerator $randomNumberGenerator): void
    {
        $this->health = Health::createRandomStatFromRange(
            $healthMin,
            $healthMax,
            $randomNumberGenerator);
        $this->strength = Strength::createRandomStatFromRange(
            $strengthMin,
            $strengthMax,
            $randomNumberGenerator);
        $this->defence = Defence::createRandomStatFromRange(
            $defenceMin,
            $defenceMax,
            $randomNumberGenerator);
        $this->speed = Speed::createRandomStatFromRange(
            $speedMin,
            $speedMax,
            $randomNumberGenerator);
        $this->luck = Luck::createRandomStatFromRange(
            $luckMin,
            $luckMax,
            $randomNumberGenerator);
    }

    /**
     * Lowers the character's health based on the provided damage
     *
     * @param int $damage
     *
     * @return void
     */
    public function takeDamage(int $damage): void
    {
        $this->health = $this->health->lowerHealthBy($damage);
    }

    /**
     * Check if the character gets lucky at the time the method is called, based on the
     * character's luck ratio
     *
     * @param IRandomNumberGenerator $rng
     *
     * @return boolean
     */
    public function isLucky(IRandomNumberGenerator $rng): bool
    {
        $probability = $rng->generateFromRange(1, 99);

        if ($this->luck->getValue() === 100
            || ($probability !== 0 && $probability <= $this->luck->getValue())) {
            return true;
        }

        return false;
    }

    /**
     * @return Health
     */
    public function getHealth(): Health
    {
        return $this->health;
    }

    /**
     * @return Strength
     */
    public function getStrength(): Strength
    {
        return $this->strength;
    }

    /**
     * @return Defence
     */
    public function getDefence(): Defence
    {
        return $this->defence;
    }

    /**
     * @return Speed
     */
    public function getSpeed(): Speed
    {
        return $this->speed;
    }

    /**
     * @return Luck
     */
    public function getLuck(): Luck
    {
        return $this->luck;
    }
}