<?php

namespace Villermen\RuneScape\ActivityFeed;

class ActivityFeedItem
{
    /**
     * Time difference between same activity reported in Adventurer's log and RuneMetrics can be up to 25 hours...
     * Adventurer's log: yesterday 0:00, RuneMetrics today 0:59. Something probably doesn't go right with timezones on
     * the Adventurer's log.
     */
    protected const COMPARISON_TIME_TOLERANCE = 25 * 60 * 60;

    public function __construct(
        protected \DateTimeImmutable $time,
        protected string $title,
        protected string $description
    ) {
        $this->title = trim($this->title);
        $this->description = trim($this->description);
    }

    public function getTime(): \DateTimeImmutable
    {
        return $this->time;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Compares this feed item to another. Only the date part of the time is compared, because adventurer's log feeds
     * only contain accurate date parts.
     */
    public function equals(ActivityFeedItem $otherItem): bool
    {
        return (
            abs($this->getTime()->getTimestamp() - $otherItem->getTime()->getTimestamp()) <= self::COMPARISON_TIME_TOLERANCE &&
            $this->getTitle() === $otherItem->getTitle() &&
            $this->getDescription() === $otherItem->getDescription()
        );
    }
}
