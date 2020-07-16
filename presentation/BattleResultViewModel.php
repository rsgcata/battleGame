<?php

declare(strict_types=1);

namespace battleGame\presentation;

use battleGame\domain\BattleRoundEnded;

class BattleResultViewModel
{
    public HeroStatsViewModel $heroStats;

    public CharacterStatsViewModel $monsterStats;

    /**
     * @var BattleRoundEnded[]
     */
    public array $roundsResults;

    public ?string $winner;

    /**
     * BattleResultViewModel constructor.
     *
     * @param HeroStatsViewModel $heroStats
     * @param CharacterStatsViewModel $monsterStats
     * @param BattleRoundEnded[] $roundsResults
     * @param string|null $winner
     */
    public function __construct(
        HeroStatsViewModel $heroStats,
        CharacterStatsViewModel $monsterStats,
        array $roundsResults,
        ?string $winner)
    {
        $this->heroStats = $heroStats;
        $this->monsterStats = $monsterStats;
        $this->roundsResults = $roundsResults;
        $this->winner = $winner;
    }
}