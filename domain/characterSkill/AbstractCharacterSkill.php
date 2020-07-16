<?php

declare(strict_types=1);

namespace battleGame\domain\characterSkill;

use DomainException;

abstract class AbstractCharacterSkill implements ICharacterSkill
{

    /**
     * The chance that the skill can occur
     */
    protected float $occurrenceChance;

    /**
     * AbstractCharacterSkill constructor.
     *
     * @param float $occurrenceChance
     */
    final public function __construct(float $occurrenceChance)
    {
        $this->setOccurrenceChance($occurrenceChance);
    }

    /**
     * {@inheritDoc}
     */
    public function getOccurrenceChance() : float
    {
        return $this->occurrenceChance;
    }

    /**
     * @param float $occurrenceChance
     *
     * @throws DomainException If the occurrence chance is invalid
     */
    protected function setOccurrenceChance(float $occurrenceChance) : void
    {
        if ($occurrenceChance < 0 || $occurrenceChance > 1) {
            throw new DomainException(
                'Could not set character skill occurrence chance. Invalid occurrence chance.');
        }

        $this->occurrenceChance = $occurrenceChance;
    }

    /**
     * {@inheritDoc}
     */
    public function getOccurrencePercentage() : int
    {
        return intval($this->occurrenceChance * 100);
    }

    /**
     * {@inheritDoc}
     */
    abstract public function getSkillName() : string;
}