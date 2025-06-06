<?php

namespace Villermen\RuneScape\HighScore;

class HighScoreComparison
{
    public function __construct(
        protected readonly HighScore $highScore1,
        protected readonly HighScore $highScore2,
    ) {
        if ($this->highScore1 instanceof OsrsHighScore !== $this->highScore2 instanceof OsrsHighScore) {
            throw new \InvalidArgumentException(
                'High score comparison can only be created between high scores of the same game version.'
            );
        }
    }

    public function getRankDifference(SkillInterface|ActivityInterface $entry): ?int
    {
        $entry1 = $entry instanceof ActivityInterface ? $this->highScore1->getActivity($entry) : $this->highScore1->getSkill($entry);
        $entry2 = $entry instanceof ActivityInterface ? $this->highScore2->getActivity($entry) : $this->highScore2->getSkill($entry);
        if ($entry1->rank === null || $entry2->rank === null) {
            return null;
        }

        return $entry2->rank - $entry1->rank;
    }

    public function getXpDifference(SkillInterface $skill): ?int
    {
        $skill1 = $this->highScore1->getSkill($skill);
        $skill2 = $this->highScore2->getSkill($skill);
        if ($skill1->xp === null || $skill2->xp === null) {
            return null;
        }

        return $skill1->xp - $skill2->xp;
    }

    public function getLevelDifference(SkillInterface $skill): ?int
    {
        $skill1 = $this->highScore1->getSkill($skill);
        $skill2 = $this->highScore2->getSkill($skill);
        if ($skill1->level === null || $skill2->level === null) {
            return null;
        }

        return $skill1->level - $skill2->level;
    }

    public function getVirtualLevelDifference(SkillInterface $skill): ?int
    {
        $skill1 = $this->highScore1->getSkill($skill);
        $skill2 = $this->highScore2->getSkill($skill);
        if ($skill1->getVirtualLevel() === null || $skill2->getVirtualLevel() === null) {
            return null;
        }

        return $skill1->getVirtualLevel() - $skill2->getVirtualLevel();
    }

    public function getScoreDifference(ActivityInterface $activity): ?int
    {
        $activity1 = $this->highScore1->getActivity($activity);
        $activity2 = $this->highScore2->getActivity($activity);
        if ($activity1->score === null || $activity2->score === null) {
            return null;
        }

        return $activity1->score - $activity2->score;
    }
}
