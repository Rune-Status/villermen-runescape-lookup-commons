<?php

namespace Villermen\RuneScape\HighScore;

/**
 * @extends HighScore<OsrsSkill, OsrsActivity>
 */
class OsrsHighScore extends HighScore
{
    public function getSkills(): array
    {
        return array_map($this->getSkill(...), OsrsSkill::cases());
    }

    public function getActivities(): array
    {
        return array_map($this->getActivity(...), OsrsActivity::cases());
    }

    public function getCombatLevel(): float
    {
        $attackLevel = $this->getSkill(OsrsSkill::ATTACK)->getLevelOrMinimum();
        $defenceLevel = $this->getSkill(OsrsSkill::DEFENCE)->getLevelOrMinimum();
        $strengthLevel = $this->getSkill(OsrsSkill::STRENGTH)->getLevelOrMinimum();
        $hitpointsLevel = $this->getSkill(OsrsSkill::HITPOINTS)->getLevelOrMinimum();
        $rangedLevel = $this->getSkill(OsrsSkill::RANGED)->getLevelOrMinimum();
        $prayerLevel = $this->getSkill(OsrsSkill::PRAYER)->getLevelOrMinimum();
        $magicLevel = $this->getSkill(OsrsSkill::MAGIC)->getLevelOrMinimum();

        return (
            max($attackLevel + $strengthLevel, $magicLevel * 1.5, $rangedLevel * 1.5) * 1.3 +
            $defenceLevel + $hitpointsLevel + floor($prayerLevel / 2)
        ) / 4;
    }
}
