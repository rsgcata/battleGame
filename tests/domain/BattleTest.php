<?php

declare(strict_types=1);

namespace battleGame\tests\domain;

use battleGame\domain\Battle;
use battleGame\domain\characterSkill\IAttackSkill;
use battleGame\domain\characterSkill\IDefenceSkill;
use battleGame\domain\characterSkill\MagicShield;
use battleGame\domain\characterSkill\RapidStrike;
use battleGame\domain\characterStat\Defence;
use battleGame\domain\characterStat\Health;
use battleGame\domain\characterStat\Luck;
use battleGame\domain\characterStat\Speed;
use battleGame\domain\characterStat\Strength;
use battleGame\domain\Hero;
use battleGame\domain\Monster;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

class BattleTest extends TestCase
{
    private MockObject $heroStub;
    private MockObject $monsterStub;
    private FakeRandomNumberGenerator $rng;

    protected function setUp(): void
    {
        $this->heroStub = $this->createStub(Hero::class);
        $this->monsterStub = $this->createStub(Monster::class);
        $this->rng = new FakeRandomNumberGenerator();
    }

    private function setHealth(Stub $characterStub, int $health): void
    {
        $healthStub = $this->createStub(Health::class);
        $healthStub->method('getValue')
            ->willReturn($health);

        $characterStub->method('getHealth')
            ->willReturn($healthStub);
    }

    private function setStrength(Stub $characterStub, int $strength): void
    {
        $strengthStub = $this->createStub(Strength::class);
        $strengthStub->method('getValue')
            ->willReturn($strength);

        $characterStub->method('getStrength')
            ->willReturn($strengthStub);
    }

    private function setLuck(Stub $characterStub, int $luck): void
    {
        $luckStub = $this->createStub(Luck::class);
        $luckStub->method('getValue')
            ->willReturn($luck);

        $characterStub->method('getLuck')
            ->willReturn($luckStub);
    }

    private function setSpeed(Stub $characterStub, int $speed): void
    {
        $speedStub = $this->createStub(Speed::class);
        $speedStub->method('getValue')
            ->willReturn($speed);

        $characterStub->method('getSpeed')
            ->willReturn($speedStub);
    }

    private function setDefence(Stub $characterStub, int $defence): void
    {
        $defenceStub = $this->createStub(Defence::class);
        $defenceStub->method('getValue')
            ->willReturn($defence);

        $characterStub->method('getDefence')
            ->willReturn($defenceStub);
    }

    private function setAttackSkills(IAttackSkill ...$skills)
    {
        $this->heroStub->method('getAttackSkills')
            ->willReturn($skills);
    }

    private function setDefenceSkills(IDefenceSkill ...$skills)
    {
        $this->heroStub->method('getDefenceSkills')
            ->willReturn($skills);
    }

    public function testMonsterStartsAsAttackerWhenMonsterHasHigherSpeed()
    {
        $this->setHealth($this->heroStub, 111);
        $this->setHealth($this->monsterStub, 111);
        $this->setSpeed($this->heroStub, 3);
        $this->setSpeed($this->monsterStub, 4);

        $battle = new Battle($this->heroStub, $this->monsterStub, 1, $this->rng);
        $rounds = $battle->runBattle();

        $this->assertFalse($rounds[0]->wasHeroAttacker());
    }

    public function testHeroStartsAsAttackerWhenHeroHasHigherSpeed()
    {
        $this->setHealth($this->heroStub, 111);
        $this->setHealth($this->monsterStub, 111);
        $this->setSpeed($this->heroStub, 4);
        $this->setSpeed($this->monsterStub, 3);

        $battle = new Battle($this->heroStub, $this->monsterStub, 1, $this->rng);
        $rounds = $battle->runBattle();

        var_dump(
            $this->heroStub->getSpeed()->getValue(),
            $this->monsterStub->getSpeed()->getValue());

        $this->assertTrue($rounds[0]->wasHeroAttacker());
    }

    public function testMonsterStartsAsAttackerWhenMonsterHasEqualSpeedButHigherLuck()
    {
        $this->setHealth($this->heroStub, 111);
        $this->setHealth($this->monsterStub, 111);
        $this->setSpeed($this->heroStub, 3);
        $this->setSpeed($this->monsterStub, 3);
        $this->setLuck($this->heroStub, 3);
        $this->setLuck($this->monsterStub, 4);

        $battle = new Battle($this->heroStub, $this->monsterStub, 1, $this->rng);
        $rounds = $battle->runBattle();

        $this->assertFalse($rounds[0]->wasHeroAttacker());
    }

    public function testHeroStartsAsAttackerWhenMonsterHasEqualSpeedButLessLuck()
    {
        $this->setHealth($this->heroStub, 111);
        $this->setHealth($this->monsterStub, 111);
        $this->setSpeed($this->heroStub, 3);
        $this->setSpeed($this->monsterStub, 3);
        $this->setLuck($this->heroStub, 4);
        $this->setLuck($this->monsterStub, 3);

        $battle = new Battle($this->heroStub, $this->monsterStub, 1, $this->rng);
        $rounds = $battle->runBattle();

        $this->assertTrue($rounds[0]->wasHeroAttacker());
    }

    public function testRunBattleAlternatesBetweenAttackers()
    {
        $this->setSpeed($this->heroStub, 4);
        $this->setSpeed($this->monsterStub, 3);
        $this->setHealth($this->heroStub, 100);
        $this->setHealth($this->monsterStub, 100);
        $this->setStrength($this->heroStub, 2);
        $this->setStrength($this->monsterStub, 2);
        $this->setDefence($this->heroStub, 1);
        $this->setDefence($this->monsterStub, 1);

        $battle = new Battle($this->heroStub, $this->monsterStub, 10, $this->rng);
        $rounds = $battle->runBattle();

        $heroIsAttacker = true;

        foreach ($rounds as $round) {
            $this->assertTrue($round->wasHeroAttacker() === $heroIsAttacker);
            $heroIsAttacker = !$heroIsAttacker;
        }
    }

    public function testRunBattleHasRoundsWhenCharactersHaveHealthLeftAndMaxRunsGreaterThanZero()
    {
        $this->setHealth($this->heroStub, 100);
        $this->setHealth($this->monsterStub, 100);

        $battle = new Battle($this->heroStub, $this->monsterStub, 1, $this->rng);
        $rounds = $battle->runBattle();

        $this->assertTrue(count($rounds) > 0);
    }

    public function testBattleEndsWhenNoTurnsLeft()
    {
        $battle = new Battle($this->heroStub, $this->monsterStub, 0, $this->rng);
        $rounds = $battle->runBattle();

        $this->assertTrue(count($rounds) === 0);
        $this->assertTrue($battle->hasBattleEnded());
    }

    public function testBattleEndsWhenMonsterHasNoHealthLeft()
    {
        $this->setHealth($this->heroStub, 100);
        $this->setHealth($this->monsterStub, 0);

        $battle = new Battle($this->heroStub, $this->monsterStub, 1, $this->rng);
        $rounds = $battle->runBattle();

        $this->assertTrue(count($rounds) === 0);
        $this->assertTrue($battle->hasBattleEnded());
    }

    public function testBattleEndsWhenHeroHasNoHealthLeft()
    {
        $this->setHealth($this->heroStub, 0);
        $this->setHealth($this->monsterStub, 100);

        $battle = new Battle($this->heroStub, $this->monsterStub, 1, $this->rng);
        $rounds = $battle->runBattle();

        $this->assertTrue(count($rounds) === 0);
        $this->assertTrue($battle->hasBattleEnded());
    }

    public function testNoDamageIsDoneWhenDefendersAreAlwaysLucky()
    {
        $health = 1111;

        $this->setHealth($this->heroStub, $health);
        $this->setHealth($this->monsterStub, $health);
        $this->setStrength($this->heroStub, 2);
        $this->setStrength($this->monsterStub, 2);
        $this->setDefence($this->heroStub, 1);
        $this->setDefence($this->monsterStub, 1);
        $this->setLuck($this->heroStub, 100);
        $this->setLuck($this->monsterStub, 100);

        $battle = new Battle($this->heroStub, $this->monsterStub, 100, $this->rng);
        $rounds = $battle->runBattle();

        foreach ($rounds as $round) {
            $this->assertSame($health, $round->getDefenderHealth());
        }
    }

    public function testSkillsAreUsedEveryTime()
    {
        $this->setHealth($this->heroStub, 1111);
        $this->setHealth($this->monsterStub, 1111);
        $this->setStrength($this->heroStub, 2);
        $this->setStrength($this->monsterStub, 2);
        $this->setDefence($this->heroStub, 1);
        $this->setDefence($this->monsterStub, 1);

        $this->heroStub->method('generateRandomAttackSkill')
            ->willReturn(new RapidStrike(1.0));
        $this->heroStub->method('generateRandomDefenceSkill')
            ->willReturn(new MagicShield(1.0));

        $battle = new Battle($this->heroStub, $this->monsterStub, 100, $this->rng);
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
        $this->setHealth($this->heroStub, 1111);
        $this->setHealth($this->monsterStub, 1111);
        $this->setStrength($this->heroStub, 2);
        $this->setStrength($this->monsterStub, 2);
        $this->setDefence($this->heroStub, 1);
        $this->setDefence($this->monsterStub, 1);

        $this->heroStub->method('generateRandomAttackSkill')
            ->willReturn(null);
        $this->heroStub->method('generateRandomDefenceSkill')
            ->willReturn(null);

        $battle = new Battle($this->heroStub, $this->monsterStub, 100, $this->rng);
        $rounds = $battle->runBattle();

        foreach ($rounds as $round) {
            $this->assertNull($round->getSkillUsedByHero());
        }

        return $rounds;
    }

    public function testDamageIsNormalWhenNoLuckOrSkillsAreInvolved()
    {
        $health = 1111;
        $str = 2;
        $def = 1;

        $this->setHealth($this->heroStub, $health);
        $this->setHealth($this->monsterStub, $health);
        $this->setStrength($this->heroStub, $str);
        $this->setStrength($this->monsterStub, $str);
        $this->setDefence($this->heroStub, $def);
        $this->setDefence($this->monsterStub, $def);
        $this->setLuck($this->heroStub, 0);
        $this->setLuck($this->monsterStub, 0);

        $this->heroStub->method('generateRandomAttackSkill')
            ->willReturn(null);
        $this->heroStub->method('generateRandomDefenceSkill')
            ->willReturn(null);

        $maxTurns = 100;
        $damage = $str - $def;

        $this->heroStub->expects($this->exactly(50))
            ->method('takeDamage')
            ->with($this->equalTo($damage));
        $this->monsterStub->expects($this->exactly(50))
            ->method('takeDamage')
            ->with($this->equalTo($damage));

        $battle = new Battle($this->heroStub, $this->monsterStub, $maxTurns, $this->rng);
        $battle->runBattle();
    }

    public function testMonsterDamageTakenIsMoreWhenSkillAlwaysUsed()
    {
        $health = 111;
        $str = 2;
        $def = 1;

        $this->setHealth($this->heroStub, $health);
        $this->setHealth($this->monsterStub, $health);
        $this->setStrength($this->heroStub, $str);
        $this->setStrength($this->monsterStub, $str);
        $this->setDefence($this->heroStub, $def);
        $this->setDefence($this->monsterStub, $def);
        $this->setLuck($this->heroStub, 0);
        $this->setLuck($this->monsterStub, 0);

        $this->heroStub->method('generateRandomAttackSkill')
            ->willReturn(new RapidStrike(1.0));

        $maxTurns = 10;
        $normalDamage = $str - $def;

        $this->monsterStub->expects($this->exactly(5))
            ->method('takeDamage')
            ->with($this->greaterThan($normalDamage));

        $battle = new Battle($this->heroStub, $this->monsterStub, $maxTurns, $this->rng);
        $battle->runBattle();
    }

    public function testHeroDamageTakenIsLessWhenSkillAlwaysUsed()
    {
        $health = 111;
        $str = 2;
        $def = 1;

        $this->setHealth($this->heroStub, $health);
        $this->setHealth($this->monsterStub, $health);
        $this->setStrength($this->heroStub, $str);
        $this->setStrength($this->monsterStub, $str);
        $this->setDefence($this->heroStub, $def);
        $this->setDefence($this->monsterStub, $def);
        $this->setLuck($this->heroStub, 0);
        $this->setLuck($this->monsterStub, 0);

        $this->heroStub->method('generateRandomDefenceSkill')
            ->willReturn(new MagicShield(1.0));

        $maxTurns = 10;
        $normalDamage = $str - $def;

        $this->heroStub->expects($this->exactly(5))
            ->method('takeDamage')
            ->with($this->lessThan($normalDamage));

        $battle = new Battle($this->heroStub, $this->monsterStub, $maxTurns, $this->rng);
        $battle->runBattle();
    }

    /**
     * @group test
     */
    public function testGetWinnerReturnsHeroWhenMonsterHealthIsZero()
    {
        $hero = Hero::createWithRandomStats(
            100,
            100,
            2,
            2,
            1,
            1,
            3,
            3,
            0,
            0,
            [],
            [],
            $this->rng);

        $monster = Monster::createWithRandomStats(
            1,
            1,
            2,
            2,
            1,
            1,
            4,
            4,
            0,
            0,
            $this->rng);

        $battle = new Battle($hero, $monster, 2, $this->rng);
        $battle->runBattle();

        $this->assertSame('hero', $battle->getWinner());
    }

    public function testGetWinnerReturnsMonsterWhenHeroHealthIsZero()
    {
        $hero = Hero::createWithRandomStats(
            1,
            1,
            2,
            2,
            1,
            1,
            3,
            3,
            0,
            0,
            [],
            [],
            $this->rng);

        $monster = Monster::createWithRandomStats(
            111,
            111,
            2,
            2,
            1,
            1,
            4,
            4,
            0,
            0,
            $this->rng);

        $battle = new Battle($hero, $monster, 2, $this->rng);
        $battle->runBattle();

        $this->assertSame('monster', $battle->getWinner());
    }

    public function testGetWinnerReturnsNullWhenNoCharacterHasHealthZero()
    {
        $hero = Hero::createWithRandomStats(
            111,
            111,
            2,
            2,
            1,
            1,
            3,
            3,
            0,
            0,
            [],
            [],
            $this->rng);

        $monster = Monster::createWithRandomStats(
            111,
            111,
            2,
            2,
            1,
            1,
            4,
            4,
            0,
            0,
            $this->rng);

        $battle = new Battle($hero, $monster, 2, $this->rng);
        $battle->runBattle();

        $this->assertNull($battle->getWinner());
    }
}