<?php

declare(strict_types=1);

namespace battleGame\domain;

use battleGame\domain\IRandomNumberGenerator;
use Exception;

class Battle
{
    /**
     * @var Hero
     */
    private Hero $hero;

    /**
     * @var Monster
     */
    private Monster $monster;

    /**
     * @var int
     */
    private int $maxTurns;

    /**
     * @var int
     */
    private int $round = 0;

    /**
     * @var IRandomNumberGenerator
     */
    private IRandomNumberGenerator $rng;

    const MAX_ALLOWED_TURNS = 20;

    /**
     * @param Hero $hero
     * @param Monster $monster
     * @param int $maxTurns The max number of turns before the battle ends if both characters
     *            have health greater than 0
     */
    public function __construct(
        Hero $hero,
        Monster $monster,
        int $maxTurns,
        IRandomNumberGenerator $rng)
    {
        $this->hero = $hero;
        $this->monster = $monster;
        $this->maxTurns = $maxTurns;
        $this->rng = $rng;
    }

    /**
     * Run the battle between the hero and the monster
     *
     * @return BattleRoundEnded[] A collection of finalized round events
     */
    public function runBattle() : array
    {
        if ($this->monster->getSpeed()->getValue() > $this->hero->getSpeed()->getValue()) {
            $attacker = $this->monster;
            $defender = $this->hero;
        } else if ($this->monster->getSpeed()->getValue() < $this->hero->getSpeed()->getValue()) {
            $attacker = $this->hero;
            $defender = $this->monster;
        } else {
            if ($this->monster->getLuck()->getValue() > $this->hero->getLuck()->getValue()) {
                $attacker = $this->monster;
                $defender = $this->hero;
            } else {
                $attacker = $this->hero;
                $defender = $this->monster;
            }
        }

        $roundEvents = [];

        while (!$this->hasBattleEnded()) {
            if ($this->round < $this->maxTurns) {
                $this->round++;
            }

            $defenderIsLucky = $defender->isLucky($this->rng);
            $skill = null;
            $initialDamageValue = 0;

            if ($defenderIsLucky) {
                $damage = 0;
            }
            else {
                $damage = $attacker->getStrength()->getValue()
                    - $defender->getDefence()->getValue();

                $initialDamageValue = $damage;

                if ($attacker instanceof Hero) {
                    $skill = $attacker->generateRandomAttackSkill($this->rng);

                    if ($skill !== null) {
                        $damage = $skill->augmentAttackDamage($damage);
                    }
                }

                if ($defender instanceof Hero) {
                    $skill = $defender->generateRandomDefenceSkill($this->rng);

                    if ($skill !== null) {
                        $damage = $skill->decreaseAttackDamage($damage);
                    }
                }
            }

            $defender->takeDamage($damage);

            $roundEndedEvent = new BattleRoundEnded(
                $this->round,
                $attacker instanceof Hero,
                $initialDamageValue,
                $skill,
                $defenderIsLucky,
                $damage,
                $damage,
                $defender->getHealth()->getValue()
            );

            $roundEvents[] = $roundEndedEvent;

            $tempAttacker = $attacker;
            $attacker = $defender;
            $defender = $tempAttacker;
        }

        return $roundEvents;
    }

    /**
     * Checks if battle has ended
     *
     * @return bool
     */
    public function hasBattleEnded() : bool
    {
        if ($this->round >= $this->maxTurns || $this->hero->getHealth()
                ->getValue() <= 0 || $this->monster->getHealth()->getValue() <= 0) {
            return true;
        }

        return false;
    }

    /**
     * Get the winner, if there is one
     *
     * @return string|null
     */
    public function getWinner() : ?string
    {
        if ($this->hero->getHealth()->getValue() === 0
            || $this->monster->getHealth()->getValue() === 0) {
            return $this->hero->getHealth()->getValue() > 0 ? 'hero' : 'monster';
        }

        return null;
    }
}