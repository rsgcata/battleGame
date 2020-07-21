<?php

declare(strict_types=1);

namespace battleGame\tests\unit\domain;

use battleGame\domain\Battle;
use battleGame\domain\BattleRoundEnded;
use battleGame\domain\characterSkill\IAttackSkill;
use battleGame\domain\characterSkill\IDefenceSkill;
use battleGame\domain\characterSkill\MagicShield;
use battleGame\domain\characterSkill\RapidStrike;
use battleGame\domain\DefaultHeroStats;
use battleGame\domain\DefaultMonsterStats;
use battleGame\domain\Hero;
use battleGame\domain\Monster;
use PHPUnit\Framework\TestCase;

class BattleTest extends TestCase
{
    private FakeRandomNumberGenerator $rng;

    protected function setUp(): void
    {
        $this->rng = new FakeRandomNumberGenerator();
    }

    private function buildMonsterWith(
        ?int $health = null,
        ?int $strength = null,
        ?int $defence = null,
        ?int $speed = null,
        ?int $luck = null): Monster
    {
        return Monster::createWithRandomStats(
            $health ?? DefaultMonsterStats::MIN_HEALTH,
            $health ?? DefaultMonsterStats::MAX_HEALTH,
            $strength ?? DefaultMonsterStats::MIN_STRENGTH,
            $strength ?? DefaultMonsterStats::MAX_STRENGTH,
            $defence ?? DefaultMonsterStats::MIN_DEFENCE,
            $defence ?? DefaultMonsterStats::MAX_DEFENCE,
            $speed ?? DefaultMonsterStats::MIN_SPEED,
            $speed ?? DefaultMonsterStats::MAX_SPEED,
            $luck ?? DefaultMonsterStats::MIN_LUCK,
            $luck ?? DefaultMonsterStats::MAX_LUCK,
            $this->rng
        );
    }

    private function buildHeroWith(
        ?int $health = null,
        ?int $strength = null,
        ?int $defence = null,
        ?int $speed = null,
        ?int $luck = null,
        array $attackSkills = [],
        array $defenceSkills = []): Hero
    {
        return Hero::createWithRandomStats(
            $health ?? DefaultHeroStats::MIN_HEALTH,
            $health ?? DefaultHeroStats::MAX_HEALTH,
            $strength ?? DefaultHeroStats::MIN_STRENGTH,
            $strength ?? DefaultHeroStats::MAX_STRENGTH,
            $defence ?? DefaultHeroStats::MIN_DEFENCE,
            $defence ?? DefaultHeroStats::MAX_DEFENCE,
            $speed ?? DefaultHeroStats::MIN_SPEED,
            $speed ?? DefaultHeroStats::MAX_SPEED,
            $luck ?? DefaultHeroStats::MIN_LUCK,
            $luck ?? DefaultHeroStats::MAX_LUCK,
            $attackSkills,
            $defenceSkills,
            $this->rng
        );
    }

    public function testMonsterStartsAsAttackerWhenMonsterHasHigherSpeed()
    {
        $hero = $this->buildHeroWith(null, null, null, 1);
        $monster = $this->buildMonsterWith(null, null, null, 2);

        $battle = new Battle($hero, $monster, 1, $this->rng);
        $rounds = $battle->runBattle();

        $this->assertFalse($rounds[0]->wasHeroAttacker());
    }

    public function testHeroStartsAsAttackerWhenHeroHasHigherSpeed()
    {
        $hero = $this->buildHeroWith(null, null, null, 2);
        $monster = $this->buildMonsterWith(null, null, null, 1);

        $battle = new Battle($hero, $monster, 1, $this->rng);
        $rounds = $battle->runBattle();

        $this->assertTrue($rounds[0]->wasHeroAttacker());
    }

    public function testMonsterStartsAsAttackerWhenMonsterHasEqualSpeedButHigherLuck()
    {
        $hero = $this->buildHeroWith(null, null, null, 1, 5);
        $monster = $this->buildMonsterWith(null, null, null, 1, 6);

        $battle = new Battle($hero, $monster, 1, $this->rng);
        $rounds = $battle->runBattle();

        $this->assertFalse($rounds[0]->wasHeroAttacker());
    }

    public function testHeroStartsAsAttackerWhenMonsterHasEqualSpeedButLessLuck()
    {
        $hero = $this->buildHeroWith(null, null, null, 1, 6);
        $monster = $this->buildMonsterWith(null, null, null, 1, 5);

        $battle = new Battle($hero, $monster, 1, $this->rng);
        $rounds = $battle->runBattle();

        $this->assertTrue($rounds[0]->wasHeroAttacker());
    }

    /**
     * @return BattleRoundEnded[]
     */
    public function testRunBattleRunsForProperNumOfRounds()
    {
        $hero = $this->buildHeroWith(100, 2, 1);
        $monster = $this->buildMonsterWith(100, 2, 1);

        $battle = $this->getMockBuilder(Battle::class)
            ->setMethodsExcept(['runBattle'])
            ->setConstructorArgs([$hero, $monster, 5, $this->rng])
            ->getMock();

        $battle->expects($this->exactly(6))
            ->method('hasBattleEnded')
            ->willReturn(false, false, false, false, false, true);

        $rounds = $battle->runBattle();

        $this->assertSame(5, count($rounds));

        return $rounds;
    }

    /**
     * @depends testRunBattleRunsForProperNumOfRounds
     *
     * @param BattleRoundEnded[] $rounds
     */
    public function testRoundsAreCounted(array $rounds)
    {
        for ($i = 1; $i <= count($rounds); $i++) {
            $this->assertSame($i, $rounds[$i - 1]->getRoundNumber());
        }
    }

    public function testNoDamageIsDoneWhenDefendersAreAlwaysLucky()
    {
        $health = 1111;

        $hero = $this->buildHeroWith($health, 2, 1, 3, 100);
        $monster = $this->buildMonsterWith($health, 2, 1, 2, 100);

        $battle = new Battle($hero, $monster, 10, $this->rng);
        $rounds = $battle->runBattle();

        foreach ($rounds as $round) {
            $this->assertTrue($round->wasDefenderLucky());
            $this->assertSame($health, $round->getDefenderHealth());
        }

        return $rounds;
    }

    public function testSkillsAreUsedEveryTimeWhenNoLuckInvolved()
    {
        $hero = $this->buildHeroWith(
            1111,
            2,
            1,
            null,
            0,
            [new RapidStrike(1.0)],
            [new MagicShield(1.0)]);
        $monster = $this->buildMonsterWith(1111, 2, 1, null, 0);

        $battle = new Battle($hero, $monster, 100, $this->rng);
        $rounds = $battle->runBattle();

        foreach ($rounds as $round) {
            if ($round->wasHeroAttacker()) {
                $this->assertInstanceOf(IAttackSkill::class, $round->getSkillUsedByHero());
            } else {
                $this->assertInstanceOf(IDefenceSkill::class, $round->getSkillUsedByHero());
            }
        }
    }

    public function testNoSkillsAreUsed()
    {
        $hero = $this->buildHeroWith();
        $monster = $this->buildMonsterWith();

        $battle = new Battle($hero, $monster, 10, $this->rng);
        $rounds = $battle->runBattle();

        foreach ($rounds as $round) {
            $this->assertNull($round->getSkillUsedByHero());
        }
    }

    public function testDamageIsNormalWhenNoLuckOrSkillsAreInvolved()
    {
        $health = 1111;
        $str = 2;
        $def = 1;

        $hero = $this->buildHeroWith($health, $str, $def, null, 0);
        $monster = $this->buildMonsterWith($health, $str, $def, null, 0);

        $maxTurns = 100;
        $damage = $str - $def;

        $battle = new Battle($hero, $monster, $maxTurns, $this->rng);
        $rounds = $battle->runBattle();

        foreach ($rounds as $round) {
            $this->assertFalse($round->wasDefenderLucky());
            $this->assertNull($round->getSkillUsedByHero());
            $this->assertSame($damage, $round->getFinalDamageValue());
        }
    }

    public function testMonsterDamageTakenIsMoreWhenSkillAlwaysUsed()
    {
        $health = 111;
        $str = 2;
        $def = 1;

        $hero = $this->buildHeroWith(
            $health,
            $str,
            $def,
            null,
            0,
            [new RapidStrike(1.0)]);
        $monster = $this->buildMonsterWith($health, $str, $def, null, 0);

        $maxTurns = 10;
        $normalDamage = $str - $def;

        $battle = new Battle($hero, $monster, $maxTurns, $this->rng);
        $rounds = $battle->runBattle();

        foreach ($rounds as $round) {
            if($round->wasHeroAttacker()) {
                $this->assertTrue($round->getFinalDamageValue() > $normalDamage);
            }
        }
    }

    public function testHeroDamageTakenIsLessWhenSkillAlwaysUsed()
    {
        $health = 111;
        $str = 2;
        $def = 1;

        $hero = $this->buildHeroWith(
            $health,
            $str,
            $def,
            null,
            0,
            [],
            [new MagicShield(1.0)]);
        $monster = $this->buildMonsterWith($health, $str, $def, null, 0);

        $maxTurns = 10;
        $normalDamage = $str - $def;

        $battle = new Battle($hero, $monster, $maxTurns, $this->rng);
        $rounds = $battle->runBattle();

        foreach ($rounds as $round) {
            if(!$round->wasHeroAttacker()) {
                $this->assertTrue($round->getFinalDamageValue() < $normalDamage);
            }
        }
    }

    public function testRunBattleAlternatesBetweenAttackers()
    {
        $hero = $this->buildHeroWith(100, 2, 1, 4);
        $monster = $this->buildMonsterWith(100, 2, 1, 3);

        $battle = new Battle($hero, $monster, 10, $this->rng);
        $rounds = $battle->runBattle();

        $heroIsAttacker = true;

        foreach ($rounds as $round) {
            $this->assertTrue($round->wasHeroAttacker() === $heroIsAttacker);
            $heroIsAttacker = !$heroIsAttacker;
        }
    }

    public function testBattleHasEndedWhenMaxRoundsReached()
    {
        $hero = $this->buildHeroWith(111);
        $monster = $this->buildMonsterWith(111);

        $battle = new Battle($hero, $monster, 0, $this->rng);

        $this->assertTrue($battle->hasBattleEnded());
    }

    public function testBattleHasEndedWhenHeroHasNoHealthLeft()
    {
        $hero = $this->buildHeroWith(0);
        $monster = $this->buildMonsterWith(111);

        $battle = new Battle($hero, $monster, 5, $this->rng);

        $this->assertTrue($battle->hasBattleEnded());
    }

    public function testBattleHasEndedWhenMonsterHasNoHealthLeft()
    {
        $hero = $this->buildHeroWith(111);
        $monster = $this->buildMonsterWith(0);

        $battle = new Battle($hero, $monster, 5, $this->rng);

        $this->assertTrue($battle->hasBattleEnded());
    }

    public function testGetWinnerReturnsHeroWhenMonsterHealthIsZero()
    {
        $hero = $this->buildHeroWith(111);
        $monster = $this->buildMonsterWith(0);

        $battle = new Battle($hero, $monster, 0, $this->rng);

        $this->assertSame('hero', $battle->getWinner());
    }

    public function testGetWinnerReturnsMonsterWhenHeroHealthIsZero()
    {
        $hero = $this->buildHeroWith(0);
        $monster = $this->buildMonsterWith(111);

        $battle = new Battle($hero, $monster, 0, $this->rng);

        $this->assertSame('monster', $battle->getWinner());
    }

    public function testGetWinnerReturnsNullWhenNoCharacterHasHealthZero()
    {
        $hero = $this->buildHeroWith(111);
        $monster = $this->buildMonsterWith(111);

        $battle = new Battle($hero, $monster, 0, $this->rng);

        $this->assertNull($battle->getWinner());
    }
}