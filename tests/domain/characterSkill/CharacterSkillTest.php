<?php

declare(strict_types=1);

namespace battleGame\tests\domain\characterSkill;

use battleGame\domain\characterSkill\AbstractCharacterSkill;
use battleGame\domain\characterSkill\MagicShield;
use battleGame\domain\characterSkill\RapidStrike;
use DomainException;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class CharacterSkillTest extends TestCase
{
    private const VALID_OCCURRENCE = 0.9;

    public function testThatAllSkillTypesAreUsingTheSameFactoryMethodForCreation()
    {
        $reflection = new ReflectionMethod(AbstractCharacterSkill::class, '__construct');

        $this->assertTrue($reflection->isFinal());
        $this->assertTrue(is_subclass_of(MagicShield::class, AbstractCharacterSkill::class));
        $this->assertTrue(is_subclass_of(RapidStrike::class, AbstractCharacterSkill::class));
    }

    public function testSkillObjectCreationFailsWhenOccurrenceBelowMin()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Invalid occurrence chance');

        $belowMinOccurrence = -0.1;

        $this->getMockBuilder(AbstractCharacterSkill::class)
            ->setConstructorArgs([$belowMinOccurrence])
            ->getMockForAbstractClass();
    }

    public function testSkillObjectCreationFailsWhenOccurrenceAboveMax()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Invalid occurrence chance');

        $aboveMaxOccurrence = 1.1;

        $this->getMockBuilder(AbstractCharacterSkill::class)
            ->setConstructorArgs([$aboveMaxOccurrence])
            ->getMockForAbstractClass();
    }

    public function testSkillObjectCreationWorks()
    {
        $mock = $this->getMockBuilder(AbstractCharacterSkill::class)
            ->setConstructorArgs([self::VALID_OCCURRENCE])
            ->getMockForAbstractClass();

        $this->assertSame(self::VALID_OCCURRENCE, $mock->getOccurrenceChance());
    }

    public function testRapidStrikeDamageAugmentWorks()
    {
        $damage = 10;
        $skill = new RapidStrike(self::VALID_OCCURRENCE);
        $increasedDamage = $skill->augmentAttackDamage($damage);

        $this->assertTrue($damage < $increasedDamage);
    }

    public function testMagicShieldDamageDecreaseWorks()
    {
        $damage = 10;
        $skill = new MagicShield(self::VALID_OCCURRENCE);
        $decreasedDamage = $skill->decreaseAttackDamage($damage);

        $this->assertTrue($damage > $decreasedDamage);
    }
}