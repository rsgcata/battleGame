<?php

declare(strict_types=1);

namespace battleGame\domain\characterSkill;

class MagicShield extends AbstractCharacterSkill implements IDefenceSkill
{
    /**
     * Decreases the provided damage value
     *
     * @param int $damageValue
     *
     * @return int The decreased damage value
     */
    public function decreaseAttackDamage(int $damageValue): int
    {
        return intval($damageValue / 2);
    }

    /**
     * {@inheritDoc}
     */
    public function getSkillName(): string
    {
        return 'Magic Shield';
    }
}