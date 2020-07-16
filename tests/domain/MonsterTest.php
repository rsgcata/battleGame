<?php

declare(strict_types=1);

namespace battleGame\tests\domain;

use battleGame\domain\DefaultMonsterStats;
use battleGame\domain\Monster;
use PHPUnit\Framework\TestCase;

class MonsterTest extends TestCase
{
    private FakeRandomNumberGenerator $rng;

    protected function setUp(): void
    {
        $this->rng = new FakeRandomNumberGenerator();
    }

    public function testCreateWithRandomStatsReturnsMonsterWithStats()
    {
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
            $this->rng);

        $this->assertInstanceOf(Monster::class, $monster);

        $this->assertTrue(
            $monster->getHealth()
                ->getValue() >= DefaultMonsterStats::MIN_HEALTH && $monster->getHealth()
                ->getValue() <= DefaultMonsterStats::MAX_HEALTH);
        $this->assertTrue(
            $monster->getDefence()
                ->getValue() >= DefaultMonsterStats::MIN_DEFENCE && $monster->getDefence()
                ->getValue() <= DefaultMonsterStats::MAX_DEFENCE);
        $this->assertTrue(
            $monster->getLuck()
                ->getValue() >= DefaultMonsterStats::MIN_LUCK && $monster->getLuck()
                ->getValue() <= DefaultMonsterStats::MAX_LUCK);
        $this->assertTrue(
            $monster->getSpeed()
                ->getValue() >= DefaultMonsterStats::MIN_SPEED && $monster->getSpeed()
                ->getValue() <= DefaultMonsterStats::MAX_SPEED);
        $this->assertTrue(
            $monster->getStrength()
                ->getValue() >= DefaultMonsterStats::MIN_STRENGTH && $monster->getStrength()
                ->getValue() <= DefaultMonsterStats::MAX_STRENGTH);
    }
}