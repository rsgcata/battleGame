<?php

declare(strict_types=1);

namespace battleGame\tests\domain\characterStat;

use battleGame\domain\characterStat\AbstractCharacterStat;
use battleGame\domain\characterStat\Defence;
use battleGame\domain\characterStat\Health;
use battleGame\domain\characterStat\Luck;
use battleGame\domain\characterStat\Speed;
use battleGame\domain\characterStat\Strength;
use battleGame\tests\domain\FakeRandomNumberGenerator;
use DomainException;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class CharacterStatTest extends TestCase
{
    private FakeRandomNumberGenerator $rng;

    protected function setUp(): void
    {
        $this->rng = new FakeRandomNumberGenerator();
    }

    public function testThatAllStatTypesAreUsingTheSameFactoryMethodForRandomStatCreation()
    {
        $reflection = new ReflectionMethod(
            AbstractCharacterStat::class,
            'createRandomStatFromRange');

        $this->assertTrue($reflection->isFinal());
        $this->assertTrue(is_subclass_of(Defence::class, AbstractCharacterStat::class));
        $this->assertTrue(is_subclass_of(Health::class, AbstractCharacterStat::class));
        $this->assertTrue(is_subclass_of(Luck::class, AbstractCharacterStat::class));
        $this->assertTrue(is_subclass_of(Speed::class, AbstractCharacterStat::class));
        $this->assertTrue(is_subclass_of(Strength::class, AbstractCharacterStat::class));
    }

    public function testCreateFromRangeThrowsExceptionWhenMinGreaterThanMax()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('min value should be less or equal to the max value');

        AbstractCharacterStat::createRandomStatFromRange(30, 20, $this->rng);
    }

    public function testCreateDefenceThrowsExceptionWhenMinNotFitGameMechanics()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('defence stat fits the game');

        Defence::createRandomStatFromRange(
            Defence::MIN_ALLOWED_DEFENCE - 1,
            Defence::MIN_ALLOWED_DEFENCE + 1,
            $this->rng);
    }

    public function testCreateHealthThrowsExceptionWhenMinNotFitGameMechanics()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('health stat fits the game');

        Health::createRandomStatFromRange(
            Health::MIN_ALLOWED_HEALTH - 1,
            Health::MIN_ALLOWED_HEALTH + 1,
            $this->rng);
    }

    public function testLowerHealthByThrowsExceptionWhenFactorNegative()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Invalid factor');

        $minHealth = Health::MIN_ALLOWED_HEALTH;
        $maxHealth = $minHealth + 10;

        $health = Health::createRandomStatFromRange(
            $minHealth,
            $maxHealth,
            $this->rng);

        $health->lowerHealthBy(-1);
    }

    public function testLowerHealthBySubtractsTheRightAmount()
    {
        $minHealth = Health::MIN_ALLOWED_HEALTH + 10;
        $maxHealth = $minHealth + 20;

        $health = Health::createRandomStatFromRange(
            $minHealth,
            $maxHealth,
            $this->rng);

        $healthToSubtract = 5;
        $newHealth = $health->lowerHealthBy($healthToSubtract);

        $this->assertSame($health->getValue() - $healthToSubtract, $newHealth->getValue());
    }

    public function testLowerHealthByStopsAtZeroWhenFactorGreaterThanHealth()
    {
        $minHealth = Health::MIN_ALLOWED_HEALTH + 10;
        $maxHealth = $minHealth + 20;

        $health = Health::createRandomStatFromRange(
            $minHealth,
            $maxHealth,
            $this->rng);

        $newHealth = $health->lowerHealthBy($health->getValue() + 1);

        $this->assertSame(0, $newHealth->getValue());
    }

    public function testCreateLuckThrowsExceptionWhenMinNotFitGameMechanics()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('luck stat fits the game');

        Luck::createRandomStatFromRange(
            Luck::MIN_ALLOWED_LUCK - 1,
            Luck::MAX_ALLOWED_LUCK,
            $this->rng);
    }

    public function testCreateLuckThrowsExceptionWhenMaxNotFitGameMechanics()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('luck stat fits the game');

        Luck::createRandomStatFromRange(
            Luck::MIN_ALLOWED_LUCK,
            Luck::MAX_ALLOWED_LUCK + 1,
            $this->rng);
    }

    public function testCreateSpeedThrowsExceptionWhenMinNotFitGameMechanics()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('speed stat fits the game');

        Speed::createRandomStatFromRange(
            Speed::MIN_ALLOWED_SPEED - 1,
            Speed::MIN_ALLOWED_SPEED,
            $this->rng);
    }

    public function testCreateStrengthThrowsExceptionWhenMinNotFitGameMechanics()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('strength stat fits the game');

        Strength::createRandomStatFromRange(
            Strength::MIN_ALLOWED_STRENGTH - 1,
            Strength::MIN_ALLOWED_STRENGTH,
            $this->rng);
    }

    public function testDefenceCreationWithProperRangeWorks()
    {
        $min = Defence::MIN_ALLOWED_DEFENCE + 10;
        $max = $min + 20;

        $defence = Defence::createRandomStatFromRange($min, $max, $this->rng);

        $this->assertTrue(
            $defence->getValue() >= $min
            && $defence->getValue() <= $max);
    }

    public function testHealthCreationWithProperRangeWorks()
    {
        $min = Health::MIN_ALLOWED_HEALTH + 10;
        $max = $min + 20;

        $health = Health::createRandomStatFromRange($min, $max, $this->rng);

        $this->assertTrue($health->getValue() >= $min && $health->getValue() <= $max);
    }
}