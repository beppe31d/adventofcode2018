<?php

namespace AdventOfCode\Entity;

/**
 * Class WarriorDistance
 * @package AdventOfCode\Entity
 */
class WarriorDistance implements WarriorInterface
{
    /** @var int */
    private $x;

    /** @var int */
    private $y;

    /** @var WarriorDistance */
    private $prev;

    /** @var string */
    private $type;

    /**
     * WarriorDistance constructor.
     * @param int $x
     * @param int $y
     * @param string $type
     * @param WarriorDistance $prev
     */
    public function __construct(int $x, int $y, string $type, WarriorDistance $prev = null)
    {
        $this->x = $x;
        $this->y = $y;
        $this->prev = $prev;
        $this->type = $type;
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
     * @return WarriorDistance
     */
    public function getPrev(): ?WarriorDistance
    {
        return $this->prev;
    }

    /**
     * @param WarriorDistance $prev
     */
    public function setPrev(WarriorDistance $prev): void
    {
        $this->prev = $prev;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
