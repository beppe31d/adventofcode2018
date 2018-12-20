<?php

namespace AdventOfCode\Entity;

/**
 * Class WaterPoint
 * @package AdventOfCode\Entity
 */
class WaterPoint
{
    /** @var int */
    private $x;

    /** @var int */
    private $y;

    /** @var WaterPoint */
    private $prev;

    /** @var bool */
    private $hasBoundaries = true;

    /**
     * WaterPoint constructor.
     * @param int $x
     * @param int $y
     * @param WaterPoint $prev
     */
    public function __construct(int $x, int $y, WaterPoint $prev = null)
    {
        $this->x = $x;
        $this->y = $y;
        $this->prev = $prev;
    }

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @param int $x
     */
    public function setX(int $x): void
    {
        $this->x = $x;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * @param int $y
     */
    public function setY(int $y): void
    {
        $this->y = $y;
    }

    /**
     * @return WaterPoint
     */
    public function getPrev(): ?WaterPoint
    {
        return $this->prev;
    }

    /**
     * @param WaterPoint $prev
     */
    public function setPrev(WaterPoint $prev): void
    {
        $this->prev = $prev;
    }

    /**
     * @return bool
     */
    public function hasBoundaries(): bool
    {
        return $this->hasBoundaries;
    }

    /**
     * @param bool $hasBoundaries
     */
    public function setBoundaries(bool $hasBoundaries): void
    {
        $this->hasBoundaries = $hasBoundaries;
    }

}
