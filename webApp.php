<?php
error_reporting(E_ALL);

// Autoload
require_once 'vendor/autoload.php';

use battleGame\domain\Battle;
use battleGame\domain\characterSkill\MagicShield;
use battleGame\domain\characterSkill\RapidStrike;
use battleGame\domain\Hero;
use battleGame\domain\Monster;
use battleGame\presentation\BattleResultViewModel;
use battleGame\presentation\CharacterStatsViewModel;
use battleGame\presentation\HeroStatsViewModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$response = new Response();
//test

if ($request->getRequestUri() === '/battle/runBattle') {
    try {
        $monster = Monster::createWithRandomStats(
            Monster::MIN_HEALTH,
            Monster::MAX_HEALTH,
            Monster::MIN_STRENGTH,
            Monster::MAX_STRENGTH,
            Monster::MIN_DEFENCE,
            Monster::MIN_SPEED,
            Monster::MAX_DEFENCE,
            Monster::MAX_SPEED,
            Monster::MIN_LUCK,
            Monster::MAX_LUCK);

        $hero = Hero::createWithRandomStats(
            Hero::MIN_HEALTH,
            Hero::MAX_HEALTH,
            Hero::MIN_STRENGTH,
            Hero::MAX_STRENGTH,
            Hero::MIN_DEFENCE,
            Hero::MAX_DEFENCE,
            Hero::MIN_SPEED,
            Hero::MAX_SPEED,
            Hero::MIN_LUCK,
            Hero::MAX_LUCK,
            [
                new RapidStrike(Hero::RAPID_STRIKE_CHANCE)
            ],
            [
                new MagicShield(Hero::MAGIC_SHIELD_CHANCE)
            ]);

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

        $battle = new Battle($hero, $monster, Battle::MAX_ALLOWED_TURNS);
        $rounds = $battle->runBattle();

        $battleResult = new BattleResultViewModel(
            $heroStats,
            $monsterStats,
            $rounds,
            $battle->getWinner());

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/json; ' . 'charset=UTF-8');
        $response->setContent(json_encode($battleResult));
    } catch (\Exception $e) {
        error_log($e->getMessage());
        $response->setStatusCode(500);
    }

    $response->send();
    exit();
} else {
    return false;
}