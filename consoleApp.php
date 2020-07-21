<?php

// Autoload
require_once('vendor/autoload.php');

use battleGame\domain\Battle;
use battleGame\domain\characterSkill\MagicShield;
use battleGame\domain\characterSkill\RapidStrike;
use battleGame\domain\DefaultHeroStats;
use battleGame\domain\DefaultMonsterStats;
use battleGame\domain\Hero;
use battleGame\domain\Monster;
use battleGame\infrastructure\domain\RandomNumberGenerator;

$rng = new RandomNumberGenerator();

$monster = Monster::createWithRandomStats(
    DefaultMonsterStats::MIN_HEALTH,
    DefaultMonsterStats::MAX_HEALTH,
    DefaultMonsterStats::MIN_STRENGTH,
    DefaultMonsterStats::MAX_STRENGTH,
    DefaultMonsterStats::MIN_DEFENCE,
    DefaultMonsterStats::MAX_DEFENCE,
    DefaultMonsterStats::MIN_SPEED,
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
        new RapidStrike(DefaultHeroStats::RAPID_STRIKE_CHANCE)
    ],
    [
        new MagicShield(DefaultHeroStats::MAGIC_SHIELD_CHANCE)
    ],
    $rng);

echo 'Setting up the Game World ...' . PHP_EOL . PHP_EOL;

sleep(1);

echo "
******************************
****     Hero Stats       ****
******************************
" . PHP_EOL . PHP_EOL;

sleep(2);

echo 'Health:   ' . $hero->getHealth()->getValue() . PHP_EOL;
echo 'Strength: ' . $hero->getStrength()->getValue() . PHP_EOL;
echo 'Defence:  ' . $hero->getDefence()->getValue() . PHP_EOL;
echo 'Speed:    ' . $hero->getSpeed()->getValue() . PHP_EOL;
echo 'Luck:     ' . $hero->getLuck()->getValue() . PHP_EOL;

echo PHP_EOL;

echo 'Attack skills: ';

$attSkillNames = '';
foreach ($hero->getAttackSkills() as $skill) {
    $attSkillNames .= $skill->getSkillName() . ', ';
}

echo rtrim($attSkillNames, ', ') . PHP_EOL;

echo 'Defence skills: ';

$defSkillNames = '';
foreach ($hero->getDefenceSkills() as $skill) {
    $defSkillNames .= $skill->getSkillName() . ', ';
}

echo rtrim($defSkillNames, ', ') . PHP_EOL;

echo PHP_EOL;

sleep(2);

echo "
******************************
****    Monster Stats     ****
******************************
" . PHP_EOL . PHP_EOL;

sleep(2);

echo 'Health:   ' . $monster->getHealth()->getValue() . PHP_EOL;
echo 'Strength: ' . $monster->getStrength()->getValue() . PHP_EOL;
echo 'Defence:  ' . $monster->getDefence()->getValue() . PHP_EOL;
echo 'Speed:    ' . $monster->getSpeed()->getValue() . PHP_EOL;
echo 'Luck:     ' . $monster->getLuck()->getValue() . PHP_EOL;

echo PHP_EOL;

sleep(2);

$battle = new Battle($hero, $monster, Battle::MAX_ALLOWED_TURNS, $rng);

try {
    $rounds = $battle->runBattle();
} catch (Exception $e) {
    echo 'We\'re sorry, but the game encountered difficulties at running the battle.'
        . 'We will try to fix this ASAP.';
    // Log $e
}

echo 'Battle will start in 5 seconds!' . PHP_EOL . PHP_EOL;

sleep(2);

for ($i = 3; $i > 0; $i--) {
    echo $i . '...' . PHP_EOL;
    sleep(1);
}

echo PHP_EOL;

foreach ($rounds as $round) {
    echo '*** ROUND: ' . $round->getRoundNumber() . ' ***' . PHP_EOL . PHP_EOL;
    usleep(700000);

    if ($round->wasHeroAttacker()) {
        if ($round->wasDefenderLucky()) {
            echo 'The Monster got lucky and blocked all the damage!' . PHP_EOL;
        } else {
            echo 'The Hero prepares to attack the Monster, dealing '
                . $round->getInitialDamageValue()
                . ' damage!' . PHP_EOL;

            usleep(700000);

            if ($round->getSkillUsedByHero() !== null) {
                echo 'The Hero used the skill ' . $round->getSkillUsedByHero()->getSkillName()
                    . '.' . ' His damage increased to ' . $round->getFinalDamageValue()
                    . ' !' . PHP_EOL;
            }
        }

        usleep(700000);

        echo 'The Monster now has ' . $round->getDefenderHealth() . ' health left (took '
            . $round->getFinalDamageValue() . ' damage).';
    } else {
        if ($round->wasDefenderLucky()) {
            echo 'The Hero got lucky and blocked all the damage dealt by the Monster!' . PHP_EOL;
        } else {
            echo 'The Monster prepares to attack the Hero, dealing '
                . $round->getInitialDamageValue() . ' damage!' . PHP_EOL;

            usleep(700000);

            if ($round->getSkillUsedByHero() !== null) {
                echo 'The Hero used the skill '
                    . $round->getSkillUsedByHero()->getSkillName()
                    . '. Monster\'s damage was decreased to '
                    . $round->getFinalDamageValue() . ' !' . PHP_EOL;
            }
        }

        usleep(700000);

        echo 'The Hero now has ' . $round->getDefenderHealth() . ' health left (took '
            . $round->getFinalDamageValue() . ' damage).';
    }

    echo PHP_EOL . PHP_EOL;
    sleep(1);
}

echo 'THE BATTLE HAS ENDED!' . PHP_EOL . PHP_EOL;

usleep(700000);

if ($battle->getWinner() === null) {
    echo 'IT\'S A TIE!' . PHP_EOL;
} else {
    echo 'THE ' . strtoupper($battle->getWinner()) . ' IS THE WINNER!!!' . PHP_EOL;
}