<?php

declare(strict_types=1);

namespace battleGame\tests\domain;

use battleGame\domain\AbstractCharacter;
use battleGame\domain\characterStat\Health;
use battleGame\domain\characterStat\Luck;
use PHPUnit\Framework\TestCase;

class AbstractCharacterTest extends TestCase
{
    private FakeRandomNumberGenerator $rng;

    protected function setUp(): void
    {
        $this->rng = new FakeRandomNumberGenerator();
    }

    private function buildCharacterWithLuck($min, $max): AbstractCharacter
    {
        return new class($min, $max, $this->rng) extends AbstractCharacter {
            public function __construct($min, $max, $rng)
            {
                $this->luck = Luck::createRandomStatFromRange($min, $max, $rng);
            }
        };
    }

    public function testCharacterTakesDamage()
    {
        $min = Health::MIN_ALLOWED_HEALTH + 10;
        $max = $min + 20;

        $character = new class($min, $max, $this->rng) extends AbstractCharacter {
            public function __construct($min, $max, $rng)
            {
                $this->health = Health::createRandomStatFromRange($min, $max, $rng);
            }
        };

        $damage = 10;
        $initialHealth = $character->getHealth()->getValue();

        $character->takeDamage($damage);

        $this->assertSame(
            $initialHealth - $damage,
            $character->getHealth()->getValue());
    }

    public function testIsLuckyAlwaysTrueWhenLuckIsMax()
    {
        $min = 100;
        $max = 100;

        $character = $this->buildCharacterWithLuck($min, $max);

        for($i = 0; $i < 100; $i++) {
            $this->assertTrue($character->isLucky($this->rng));
        }
    }

    public function testIsLuckyNotAlwaysTrueWhenLuckIsNotMax()
    {
        $min = 50;
        $max = 50;

        $character = $this->buildCharacterWithLuck($min, $max);

        $luckCount = 0;
        $numOfTries = 100;

        for($i = 0; $i < $numOfTries; $i++) {
            if ($character->isLucky($this->rng)) {
                $luckCount++;
            }
        }

        $this->assertNotSame($numOfTries, $luckCount);
    }

    public function testIsLuckyAlwaysFalseWhenLuckIsMin()
    {
        $min = 0;
        $max = 0;

        $character = $this->buildCharacterWithLuck($min, $max);

        $luckCount = 0;

        for($i = 0; $i < 100; $i++) {
            if ($character->isLucky($this->rng)) {
                $luckCount++;
            }
        }

        $this->assertSame(0, $luckCount);
    }
}