<?php
error_reporting(E_ALL);

// Autoload
require_once 'vendor/autoload.php';

use battleGame\domain\Battle;
use battleGame\domain\characterSkill\MagicShield;
use battleGame\domain\characterSkill\RapidStrike;
use battleGame\domain\DefaultHeroStats;
use battleGame\domain\DefaultMonsterStats;
use battleGame\domain\Hero;
use battleGame\domain\Monster;
use battleGame\infrastructure\domain\RandomNumberGenerator;
use battleGame\presentation\BattleResultViewModel;
use battleGame\presentation\CharacterStatsViewModel;
use battleGame\presentation\HeroStatsViewModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$response = new Response();

if ($request->getRequestUri() === '/battle/runBattle') {
    try {
        $rng = new RandomNumberGenerator();

        $monster = Monster::createWithRandomStats(
            DefaultMonsterStats::MIN_HEALTH,
            DefaultMonsterStats::MAX_HEALTH,
            DefaultMonsterStats::MIN_STRENGTH,
            DefaultMonsterStats::MAX_STRENGTH,
            DefaultMonsterStats::MIN_DEFENCE,
            DefaultMonsterStats::MIN_SPEED,
            DefaultMonsterStats::MAX_DEFENCE,
            DefaultMonsterStats::MAX_SPEED,
            DefaultMonsterStats::MIN_LUCK,
            DefaultMonsterStats::MAX_LUCK,
            $rng);

        $hero = Hero::createWithRandomStats(
            DefaultHeroStats::MIN_HEALTH,
            DefaultHeroStats::MAX_HEALTH,
            DefaultHeroStats::MIN_STRENGTH,
            DefaultHeroStats::MAX_STRENGTH,
            DefaultHeroStats::MIN_DEFENCE,
            DefaultHeroStats::MAX_DEFENCE,
            DefaultHeroStats::MIN_SPEED,
            DefaultHeroStats::MAX_SPEED,
            DefaultHeroStats::MIN_LUCK,
            DefaultHeroStats::MAX_LUCK,
            [
                new RapidStrike(Hero::RAPID_STRIKE_CHANCE)
            ],
            [
                new MagicShield(Hero::MAGIC_SHIELD_CHANCE)
            ],
            $rng);

        $attackSkills = $defenceSkills = [];

        foreach ($hero->getAttackSkills() as $skill) {
            $attackSkills[] = $skill->getSkillName();
        }

        foreach ($hero->getDefenceSkills() as $skill) {
            $defenceSkills[] = $skill->getSkillName();
        }

        $heroStats = new HeroStatsViewModel(
            $hero->getHealth()->getValue(),
            $hero->getDefence()->getValue(),
            $hero->getStrength()->getValue(),
            $hero->getSpeed()->getValue(),
            $hero->getLuck()->getValue(),
            $attackSkills,
            $defenceSkills);

        $monsterStats = new CharacterStatsViewModel(
            $monster->getHealth()->getValue(),
            $monster->getDefence()->getValue(),
            $monster->getStrength()->getValue(),
            $monster->getSpeed()->getValue(),
            $monster->getLuck()->getValue());

        $battle = new Battle($hero, $monster, Battle::MAX_ALLOWED_TURNS, $rng);
        $rounds = $battle->runBattle();

        $battleResult = new BattleResultViewModel(
            $heroStats,
            $monsterStats,
            $rounds,
            $battle->getWinner());

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/json; ' . 'charset=UTF-8');
        $response->setContent(json_encode($battleResult));
    } catch (Exception $e) {
        error_log($e->getMessage());
        $response->setStatusCode(500);
    }

    $response->send();
    exit();
} else {
    return false;
}