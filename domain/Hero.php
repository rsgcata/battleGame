<?php

declare(strict_types=1);

namespace battleGame\domain;

use battleGame\domain\characterSkill\IAttackSkill;
use battleGame\domain\characterSkill\ICharacterSkill;
use battleGame\domain\characterSkill\IDefenceSkill;
use Exception;

class Hero extends AbstractCharacter
{
    /**
     * @var IAttackSkill[]
     */
    private array $attackSkills = [];

    /**
     * @var IDefenceSkill[]
     */
    private array $defenceSkills = [];

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
     * @param IAttackSkill[] $attackSkills
     * @param IDefenceSkill[] $defenceSkills
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
        array $attackSkills,
        array $defenceSkills,
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

        $self->setAttackSkills(...$attackSkills);
        $self->setDefenceSkills(...$defenceSkills);

        return $self;
    }

    /**
     * @param IDefenceSkill ...$defenceSkills
     * @return void
     */
    private function setDefenceSkills(IDefenceSkill ...$defenceSkills) : void
    {
        $this->defenceSkills = $defenceSkills;
    }

    /**
     * @param IAttackSkill ...$attackSkills
     * @return void
     */
    private function setAttackSkills(IAttackSkill ...$attackSkills) : void
    {
        $this->attackSkills = $attackSkills;
    }

    /**
     * Generate a random attack skill from the hero attack skills collection
     *
     * @param IRandomNumberGenerator $rng
     *
     * @return IAttackSkill|null
     */
    public function generateRandomAttackSkill(IRandomNumberGenerator $rng) : ?IAttackSkill
    {
        return $this->generateRandomSkill($rng, ...$this->attackSkills);
    }

    /**
     * Generate a random defence skill from the hero defence skills collection
     *
     * @param IRandomNumberGenerator $rng
     *
     * @return IDefenceSkill|null
     */
    public function generateRandomDefenceSkill(IRandomNumberGenerator $rng)
    {
        return $this->generateRandomSkill($rng, ...$this->defenceSkills);
    }

    /**
     * Generate a random skill from a collection of skills
     * Picks the one that has lowest occurrence chance if any fits the probability range
     *
     * @param IRandomNumberGenerator $rng
     * @param ICharacterSkill[] $skillCollection
     *
     * @return IAttackSkill|IDefenceSkill|null
     */
    private function generateRandomSkill(
        IRandomNumberGenerator $rng,
        ICharacterSkill ...$skillCollection) : ?ICharacterSkill
    {
        $probability = $rng->generateFromRange(0, 99);

        /* @var $skillToUse ICharacterSkill */
        $skillToUse = null;

        // Iterate over the skills and pick only the one that can occur and has the
        // lowest probability (means it's a better skill)
        foreach ($skillCollection as $skill) {
            if ($skill->getOccurrencePercentage() === 100
                || ($probability !== 0 && $probability <= $skill->getOccurrencePercentage())) {
                if ($skillToUse === null
                    || $skillToUse->getOccurrenceChance() > $skill->getOccurrenceChance()) {
                    $skillToUse = $skill;
                }
            }
        }

        return $skillToUse;
    }

    /**
     * @return IAttackSkill[]
     */
    public function getAttackSkills() : array
    {
        return $this->attackSkills;
    }

    /**
     * @return IDefenceSkill[]
     */
    public function getDefenceSkills() : array
    {
        return $this->defenceSkills;
    }
}