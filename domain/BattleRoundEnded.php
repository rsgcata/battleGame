<?php

declare(strict_types=1);

namespace battleGame\domain;

use battleGame\domain\characterSkill\ICharacterSkill;
use JsonSerializable;

/**
 * @author George Catalin
 */
class BattleRoundEnded implements JsonSerializable
{
    protected int $roundNumber;

    protected bool $heroWasAttacker;

    protected int $initialDamageValue;

    /**
     * @var ICharacterSkill|null
     */
    protected ?ICharacterSkill $skillUsedByHero;

    protected bool $defenderWasLucky;

    protected int $damageAfterSkill;

    protected int $finalDamageValue;

    protected int $defenderHealth;

    /**
     * BattleRoundEnded constructor.
     *
     * @param int $roundNumber
     * @param bool $heroWasAttacker
     * @param int $initialDamageValue
     * @param ICharacterSkill|null $skillUsedByHero
     * @param bool $defenderWasLucky
     * @param int $damageAfterSkill
     * @param int $finalDamageValue
     * @param int $defenderHealth
     */
    public function __construct(
        int $roundNumber,
        bool $heroWasAttacker,
        int $initialDamageValue,
        ?ICharacterSkill $skillUsedByHero,
        bool $defenderWasLucky,
        int $damageAfterSkill,
        int $finalDamageValue,
        int $defenderHealth)
    {
        $this->roundNumber = $roundNumber;
        $this->heroWasAttacker = $heroWasAttacker;
        $this->initialDamageValue = $initialDamageValue;
        $this->skillUsedByHero = $skillUsedByHero;
        $this->defenderWasLucky = $defenderWasLucky;
        $this->damageAfterSkill = $damageAfterSkill;
        $this->finalDamageValue = $finalDamageValue;
        $this->defenderHealth = $defenderHealth;
    }

    /**
     * @return int
     */
    public function getRoundNumber(): int
    {
        return $this->roundNumber;
    }

    /**
     * @return bool
     */
    public function wasHeroAttacker(): bool
    {
        return $this->heroWasAttacker;
    }

    /**
     * @return int
     */
    public function getInitialDamageValue(): int
    {
        return $this->initialDamageValue;
    }

    /**
     * @return ICharacterSkill|null
     */
    public function getSkillUsedByHero(): ?ICharacterSkill
    {
        return $this->skillUsedByHero;
    }

    /**
     * @return bool
     */
    public function wasDefenderLucky(): bool
    {
        return $this->defenderWasLucky;
    }

    /**
     * @return int
     */
    public function getDamageAfterSkill(): int
    {
        return $this->damageAfterSkill;
    }

    /**
     * @return int
     */
    public function getFinalDamageValue(): int
    {
        return $this->finalDamageValue;
    }

    /**
     * @return int
     */
    public function getDefenderHealth(): int
    {
        return $this->defenderHealth;
    }

    public function jsonSerialize() : array
    {
        return [
            'roundNumber' => $this->roundNumber,
            'heroWasAttacker' => $this->heroWasAttacker,
            'initialDamageValue' => $this->initialDamageValue,
            'skillUsedByHero' => $this->skillUsedByHero !== null
                ? $this->skillUsedByHero->getSkillName() : null,
            'defenderWasLucky' => $this->defenderWasLucky,
            'damageAfterSkill' => $this->damageAfterSkill,
            'finalDamageValue' => $this->finalDamageValue,
            'defenderHealth' => $this->defenderHealth
        ];
    }
}