<?php

namespace Villermen\RuneScape\Test;

use PHPUnit\Framework\TestCase;
use Villermen\RuneScape\HighScore\HighScoreActivity;
use Villermen\RuneScape\HighScore\HighScoreSkill;
use Villermen\RuneScape\HighScore\OsrsActivity;
use Villermen\RuneScape\HighScore\OsrsHighScore;
use Villermen\RuneScape\HighScore\OsrsSkill;
use Villermen\RuneScape\HighScore\Rs3Activity;
use Villermen\RuneScape\HighScore\Rs3HighScore;
use Villermen\RuneScape\HighScore\Rs3Skill;

class HighScoreTest extends TestCase
{
    private Rs3HighScore $highScore1;

    private Rs3HighScore $highScore2;

    private OsrsHighScore $highScore3;

    public function setUp(): void
    {
        $this->highScore1 = new Rs3HighScore([
            new HighScoreSkill(
                skill: Rs3Skill::INVENTION,
                rank: 1999999,
                level: 120,
                xp: 104772129,
            ),
            new HighScoreSkill(
                skill: Rs3Skill::MINING,
                rank: null,
                level: 108,
                xp: 33566929,
            ),
            new HighScoreSkill(
                skill: Rs3Skill::TOTAL,
                rank: null,
                level: 2943,
                xp:  2067425433,
            ),
            new HighScoreSkill(
                skill: Rs3Skill::NECROMANCY,
                rank: null,
                level: 68,
                xp: 1200,
            ),
            new HighScoreSkill(
                skill: Rs3Skill::SUMMONING,
                rank: null,
                level: 68,
                xp: 0,
            ),
        ], [
            new HighScoreActivity(
                activity: Rs3Activity::DOMINION_TOWER,
                rank: null,
                score: 1362803,
            ),
            new HighScoreActivity(
                activity: Rs3Activity::WORLD_EVENT_2_BANDOS_KILLS,
                rank: null,
                score: 0,
            ),
        ]);

        $this->highScore2 = new Rs3HighScore([
            new HighScoreSkill(
                skill: Rs3Skill::ATTACK,
                rank: null,
                level: 68,
                xp: 0,
            ),
            new HighScoreSkill(
                skill: Rs3Skill::MINING,
                rank: null,
                level: 80,
                xp: 33567929,
            ),
            new HighScoreSkill(
                skill: Rs3Skill::TOTAL,
                rank: 1200000,
                level: 2743,
                xp:  2167425433,
            ),
        ], [
            new HighScoreActivity(
                activity: Rs3Activity::DOMINION_TOWER,
                rank: null,
                score: 1200,
            ),
        ]);

        $this->highScore3 = new OsrsHighScore([
            new HighScoreSkill(
                skill: OsrsSkill::MAGIC,
                rank: null,
                level: 68,
                xp: 0,
            ),
            new HighScoreSkill(
                skill: OsrsSkill::MINING,
                rank: null,
                level: 80,
                xp: 33567929,
            ),
        ], [
            new HighScoreActivity(
                activity: OsrsActivity::ZULRAH,
                rank: 1,
                score: 1362803,
            ),
            new HighScoreActivity(
                activity: OsrsActivity::LEAGUE_POINTS,
                rank: null,
                score: 0,
            ),
        ]);
    }

    public function testEntries(): void
    {
        self::assertEquals(108, $this->highScore1->getSkill(Rs3Skill::MINING)->getLevel());
        self::assertEquals(2943, $this->highScore1->getSkill(Rs3Skill::TOTAL)->getLevel());
        // Total level doesn't do virtual.
        self::assertEquals(2943, $this->highScore1->getSkill(Rs3Skill::TOTAL)->getVirtualLevel());
        self::assertEquals(null, $this->highScore1->getSkill(Rs3Skill::TOTAL)->getRank());
        // Unranked
        self::assertEquals(null, $this->highScore1->getSkill(Rs3Skill::DEFENCE)->getXp());
        self::assertEquals(null, $this->highScore1->getSkill(Rs3Skill::DEFENCE)->getLevel());
        self::assertEquals(null, $this->highScore1->getSkill(Rs3Skill::DEFENCE)->getRank());
        // OSRS skill mapping compatibility.
        // @phpstan-ignore argument.type
        self::assertEquals(33566929, $this->highScore1->getSkill(OsrsSkill::MINING)->getXp());
        // @phpstan-ignore argument.type
        self::assertEquals(108, $this->highScore1->getSkill(OsrsSkill::MINING)->getLevel());

        $invSkill = $this->highScore1->getSkill(Rs3Skill::INVENTION);
        self::assertEquals(120, $invSkill->getLevel());
        self::assertEquals(128, $invSkill->getVirtualLevel());
        self::assertEquals(104772129, $invSkill->getXp());
        self::assertEquals(1999999, $invSkill->getRank());
        self::assertSame(Rs3Skill::INVENTION, $invSkill->getSkill());
        self::assertEquals(2992087, $invSkill->getXpToNextLevel());
        self::assertEquals(0.0904, round($invSkill->getProgressToNextLevel() ?? 0.0, 4));

        self::assertEquals(1362803, $this->highScore1->getActivity(Rs3Activity::DOMINION_TOWER)->getScore());
        self::assertEquals(null, $this->highScore1->getActivity(Rs3Activity::DOMINION_TOWER)->getRank());
        self::assertEquals(0, $this->highScore1->getActivity(Rs3Activity::WORLD_EVENT_2_BANDOS_KILLS)->getScore());
        // Out of bound OSRS activity.
        // @phpstan-ignore argument.type
        self::assertEquals(null, $this->highScore1->getActivity(OsrsActivity::ZULRAH)->getScore());

        self::assertEquals(55.45, $this->highScore1->getCombatLevel());
        self::assertEquals(46.95, $this->highScore1->getCombatLevel(includeSummoning: false));
        self::assertEquals(35.9, $this->highScore3->getCombatLevel());
    }

    public function testComparison(): void
    {
        $comparison = $this->highScore1->compareTo($this->highScore2);

        self::assertEquals(200, $comparison->getLevelDifference(Rs3Skill::TOTAL));
        self::assertEquals(-100000000, $comparison->getXpDifference(Rs3Skill::TOTAL));
        self::assertEquals(null, $comparison->getRankDifference(Rs3Skill::TOTAL));

        self::assertEquals(28, $comparison->getLevelDIfference(Rs3Skill::MINING));
        self::assertEquals(0, $comparison->getVirtualLevelDifference(Rs3Skill::MINING));

        self::assertEquals(1361603, $comparison->getScoreDifference(Rs3Activity::DOMINION_TOWER));
        self::assertEquals(null, $comparison->getRankDifference(Rs3Activity::DOMINION_TOWER));
    }
}
