<?php

declare(strict_types=1);

namespace battleGame\domain\characterSkill;

interface IDefenceSkill extends ICharacterSkill
{

    /**
     * Decreases the provided damage value
     *
     * @param int $damageValue
     *
     * @return int The decreased damage value
     */
    public function decreaseAttackDamage(int $damageValue) : int;
}