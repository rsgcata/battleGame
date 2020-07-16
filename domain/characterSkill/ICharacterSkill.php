<?php

declare(strict_types=1);


namespace battleGame\domain\characterSkill;


interface ICharacterSkill
{
    /**
     * Get the occurrence chance as float
     */
    public function getOccurrenceChance() : float;

    /**
     * Returns the occurrence chance as a percentage
     */
    public function getOccurrencePercentage() : int;

    /**
     * Get the name of the skill
     */
    public function getSkillName() : string;
}