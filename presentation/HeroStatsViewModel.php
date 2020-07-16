<?php

declare(strict_types=1);

namespace battleGame\presentation;

class HeroStatsViewModel extends CharacterStatsViewModel
{
    /**
     * @var string[]
     */
    public array $attackSkills;

    /**
     * @var string[]
     */
    public array $defenceSkills;

    /**
     * HeroStatsViewModel constructor.
     *
     * @param int $health
     * @param int $defence
     * @param int $strength
     * @param int $speed
     * @param int $luck
     * @param string[] $attackSkills
     * @param string[] $defenceSkills
     */
    public function __construct(
        int $health,
        int $defence,
        int $strength,
        int $speed,
        int $luck,
        array $attackSkills,
        array $defenceSkills)
    {
        parent::__construct($health, $defence, $strength, $speed, $luck);
        $this->attackSkills = $attackSkills;
        $this->defenceSkills = $defenceSkills;
    }
}