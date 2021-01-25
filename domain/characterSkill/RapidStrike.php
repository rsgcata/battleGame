<?php

declare(strict_types=1);

namespace battleGame\domain\characterSkill;

class RapidStrike extends AbstractCharacterSkill implements IAttackSkill
{
    /**
     * {@inheritDoc}
     */
    public function augmentAttackDamage(int $damageValue): int
    {
        return $damageValue * 2;
    }

    /**
     * {@inheritDoc}
     */
    public function getSkillName(): string
    {
        return 'Rapid Strike';
    }
}