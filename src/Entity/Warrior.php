<?php

namespace AdventOfCode\Entity;

/**
 * Class Warrior
 * @package AdventOfCode\Entity
 */
class Warrior implements WarriorInterface
{
    /** @var int */
    private $hp;

    /** @var int */
    private $attack;

    /** @var string */
    private $type;

    /**
     * Warrior constructor.
     * @param int $x
     * @param int $y
     * @param string $type
     * @param int $attack
     */
    public function __construct(int $x, int $y, string $type, int $attack = 3)
    {
        $this->x = $x;
        $this->y = $y;
        $this->type = $type;
        $this->hp = 200;
        $this->attack = $attack;
    }

    public function __toString(): string
    {
        return $this->type;
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
     * @return int
     */
    public function getHp(): int
    {
        return $this->hp;
    }

    /**
     * @param int $hp
     */
    public function setHp(int $hp): void
    {
        $this->hp = $hp;
    }

    /**
     * @return int
     */
    public function getAttack(): int
    {
        return $this->attack;
    }

    /**
     * @param int $attack
     */
    public function setAttack(int $attack): void
    {
        $this->attack = $attack;
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

    /**
     * @param Warrior $enemy
     */
    public function attack(Warrior $enemy): void
    {
        $enemy->setHp($enemy->getHp() - $this->attack);
    }
}
