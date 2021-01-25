<?php

declare(strict_types=1);

namespace battleGame\domain\characterSkill;

interface IAttackSkill extends ICharacterSkill
{

    /**
     * Augments (increase) the provided damage
     *
     * @param int $damageValue
     *
     * @return int The augmented damage value
     */
    public function augmentAttackDamage(int $damageValue): int;
}