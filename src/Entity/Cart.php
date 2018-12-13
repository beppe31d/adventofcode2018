<?php

namespace AdventOfCode\Entity;

/**
 * Class Cart
 * @package AdventOfCode\Entity
 */
class Cart
{
    /** @var int */
    private $x;

    /** @var int */
    private $y;

    /** @var string */
    private $direction;

    /** @var string */
    private $nextIntesection;

    /**
     * Cart constructor.
     * @param int $x
     * @param int $y
     * @param string $direction
     * @param string $nextIntesection
     */
    public function __construct(int $x, int $y, string $direction)
    {
        $this->x = $x;
        $this->y = $y;
        $this->direction = $direction;
        $this->nextIntesection = 'left';
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
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     */
    public function setDirection(string $direction): void
    {
        $this->direction = $direction;
    }

    /**
     * @return string
     */
    public function getNextIntesection(): string
    {
        return $this->nextIntesection;
    }

    /**
     * @param string $nextIntesection
     */
    public function setNextIntesection(string $nextIntesection): void
    {
        $this->nextIntesection = $nextIntesection;
    }

    public function tick(): void
    {
        switch ($this->direction) {
            case '^':
                $this->y--;
                break;
            case 'v':
                $this->y++;
                break;
            case '<':
                $this->x--;
                break;
            case '>':
                $this->x++;
                break;
        }
    }

    /**
     * @param string $value
     */
    public function changeDirection(string $value): void
    {
        switch ($this->direction) {
            case '^':
                $this->upMove($value);
                break;
            case 'v':
                $this->downMove($value);
                break;
            case '<':
                $this->leftMove($value);
                break;
            case '>':
                $this->rightMove($value);
                break;
        }

        if ($value === '+') {
            switch ($this->nextIntesection) {
                case 'left':
                    $this->nextIntesection = 'straight';
                    break;
                case 'straight':
                    $this->nextIntesection = 'right';
                    break;
                case 'right':
                    $this->nextIntesection = 'left';
                    break;
            }
        }
    }

    /**
     * @param string $value
     */
    private function upMove(string $value): void
    {
        if ($value === '/' || ($value === '+' && $this->nextIntesection === 'right')) {
            $this->direction = '>';
            return;
        }

        if ($value === '\\' || ($value === '+' && $this->nextIntesection === 'left')) {
            $this->direction = '<';
            return;
        }
    }

    /**
     * @param string $value
     */
    private function leftMove(string $value): void
    {
        if ($value === '/' || ($value === '+' && $this->nextIntesection === 'left')) {
            $this->direction = 'v';
            return;
        }

        if ($value === '\\' || ($value === '+' && $this->nextIntesection === 'right')) {
            $this->direction = '^';
            return;
        }
    }

    /**
     * @param string $value
     */
    private function downMove(string $value): void
    {
        if ($value === '/' || ($value === '+' && $this->nextIntesection === 'right')) {
            $this->direction = '<';
            return;
        }

        if ($value === '\\' || ($value === '+' && $this->nextIntesection === 'left')) {
            $this->direction = '>';
            return;
        }
    }

    /**
     * @param string $value
     */
    private function rightMove(string $value): void
    {
        if ($value === '/' || ($value === '+' && $this->nextIntesection === 'left')) {
            $this->direction = '^';
            return;
        }

        if ($value === '\\' || ($value === '+' && $this->nextIntesection === 'right')) {
            $this->direction = 'v';
            return;
        }
    }

    public function equals(Cart $point) {
        return $this->x === $point->getX() && $this->y === $point->getY();
    }
}
