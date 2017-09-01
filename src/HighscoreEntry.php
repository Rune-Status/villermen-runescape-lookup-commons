<?php

namespace Villermen\RuneScape;

/**
 * Represents a skill or activity in a highscore.
 */
abstract class HighscoreEntry
{
    /** @var int|false */
    private $rank;

    /**
     * @param int|false $rank
     */
    public function __construct($rank)
    {
        $this->setRank($rank);
    }

    /**
     * Returns the name of the stat for display purposes.
     *
     * @return string
     */
    abstract public function getName(): string;

    /**
     * @param int|false $rank
     *
     * @return HighscoreEntry
     */
    public function setRank($rank): HighscoreEntry
    {
        if (!is_int($rank) || $rank < 1) {
            $rank = false;
        }

        $this->rank = $rank;

        return $this;
    }

    /**
     * @return int|false
     */
    public function getRank()
    {
        return $this->rank;
    }
}
