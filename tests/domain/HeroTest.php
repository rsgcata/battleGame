<?php

declare(strict_types=1);

namespace battleGame\tests\domain;

use battleGame\domain\characterSkill\IAttackSkill;
use battleGame\domain\characterSkill\IDefenceSkill;
use battleGame\domain\characterSkill\MagicShield;
use battleGame\domain\characterSkill\RapidStrike;
use battleGame\domain\DefaultHeroStats;
use battleGame\domain\Hero;
use PHPUnit\Framework\TestCase;
use TypeError;

class HeroTest extends TestCase
{
    private FakeRandomNumberGenerator $rng;

    protected function setUp(): void
    {
        $this->rng = new FakeRandomNumberGenerator();
    }

    private function buildStatsArgs(): array
    {
        return [
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
        ];
    }

    public function testCreateWithRandomStatsReturnsHeroWithStatsAndSkills()
    {
        $hero = Hero::createWithRandomStats(
            ...[
                   ...$this->buildStatsArgs(),
                   [
                       new RapidStrike(0.7)
                   ],
                   [
                       new MagicShield(0.7)
                   ],
                   $this->rng
               ]);

        $this->assertInstanceOf(Hero::class, $hero);

        $this->assertTrue(
            $hero->getHealth()->getValue() <= DefaultHeroStats::MAX_HEALTH
            && $hero->getHealth()->getValue() >= DefaultHeroStats::MIN_HEALTH);
        $this->assertTrue(
            $hero->getDefence()->getValue() <= DefaultHeroStats::MAX_DEFENCE
            && $hero->getDefence()->getValue() >= DefaultHeroStats::MIN_DEFENCE);
        $this->assertTrue(
            $hero->getLuck()->getValue() <= DefaultHeroStats::MAX_LUCK
            && $hero->getLuck()->getValue() >= DefaultHeroStats::MIN_LUCK);
        $this->assertTrue(
            $hero->getSpeed()->getValue() <= DefaultHeroStats::MAX_SPEED
            && $hero->getSpeed()->getValue() >= DefaultHeroStats::MIN_SPEED);
        $this->assertTrue(
            $hero->getStrength()->getValue() <= DefaultHeroStats::MAX_STRENGTH
            && $hero->getStrength()->getValue() >= DefaultHeroStats::MIN_STRENGTH);

        $this->assertCount(1, $hero->getAttackSkills());
        $this->assertCount(1, $hero->getDefenceSkills());

        foreach ($hero->getAttackSkills() as $skill) {
            $this->assertInstanceOf(IAttackSkill::class, $skill);
            $this->assertSame(0.7, $skill->getOccurrenceChance());
        }

        foreach ($hero->getDefenceSkills() as $skill) {
            $this->assertInstanceOf(IDefenceSkill::class, $skill);
            $this->assertSame(0.7, $skill->getOccurrenceChance());
        }
    }

    public function testCreateWithRandomStatsFailsWhenInvalidAttackSkills()
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('IAttackSkill');

        Hero::createWithRandomStats(
            ...[
                   ...$this->buildStatsArgs(),
                   [
                       new MagicShield(0.7)
                   ],
                   [],
                   $this->rng
               ]);
    }

    public function testCreateWithRandomStatsFailsWhenInvalidDefenceSkills()
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('IDefenceSkill');

        Hero::createWithRandomStats(
            ...[
                   ...$this->buildStatsArgs(),
                   [],
                   [
                       new RapidStrike(0.7)
                   ],
                   $this->rng
               ]);
    }

    public function testGenerateRandomAttackSkillAlwaysReturnsSkillWhenOccurrenceChanceIsMax()
    {
        $hero = Hero::createWithRandomStats(
            ...[
                   ...$this->buildStatsArgs(),
                   [
                       new RapidStrike(1.0)
                   ],
                   [],
                   $this->rng
               ]);

        $skillCount = 0;
        $numOfTries = 100;

        for ($i = 0; $i < $numOfTries; $i++) {
            if ($hero->generateRandomAttackSkill($this->rng) !== null) {
                $skillCount++;
            }
        }

        $this->assertSame($numOfTries, $skillCount);
    }

    public function testGenerateRandomAttackSkillNotAlwaysReturnsSkillWhenOccurrenceChanceIsNotMax()
    {
        $hero = Hero::createWithRandomStats(
            ...[
                   ...$this->buildStatsArgs(),
                   [
                       new RapidStrike(0.5)
                   ],
                   [],
                   $this->rng
               ]);

        $skillCount = 0;
        $numOfTries = 100;

        for ($i = 0; $i < $numOfTries; $i++) {
            if ($hero->generateRandomAttackSkill($this->rng) !== null) {
                $skillCount++;
            }
        }

        $this->assertTrue($skillCount < $numOfTries);
    }

    public function testGenerateRandomDefenceSkillAlwaysReturnsSkillWhenOccurrenceChanceIsMax()
    {
        $hero = Hero::createWithRandomStats(
            ...[
                   ...$this->buildStatsArgs(),
                   [],
                   [
                       new MagicShield(1.0)
                   ],
                   $this->rng
               ]);

        $skillCount = 0;
        $numOfTries = 100;

        for ($i = 0; $i < $numOfTries; $i++) {
            if ($hero->generateRandomDefenceSkill($this->rng) !== null) {
                $skillCount++;
            }
        }

        $this->assertSame($numOfTries, $skillCount);
    }

    public function testGenerateRandomDefenceSkillNotAlwaysReturnsSkillWhenOccurrenceChanceIsNotMax()
    {
        $hero = Hero::createWithRandomStats(
            ...[
                   ...$this->buildStatsArgs(),
                   [],
                   [
                       new MagicShield(0.5)
                   ],
                   $this->rng
               ]);

        $skillCount = 0;
        $numOfTries = 100;

        for ($i = 0; $i < $numOfTries; $i++) {
            if ($hero->generateRandomDefenceSkill($this->rng) !== null) {
                $skillCount++;
            }
        }

        $this->assertTrue($skillCount < $numOfTries);
    }
}