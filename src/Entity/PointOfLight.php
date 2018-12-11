<?php

namespace AdventOfCode\Entity;

class PointOfLight
{
    /** @var int */
    private $positionX;

    /** @var int */
    private $positionY;

    /** @var int */
    private $velocityX;

    /** @var int */
    private $velocityY;

    /**
     * PointOfLight constructor.
     * @param int $positionX
     * @param int $positionY
     * @param int $velocityX
     * @param int $velocityY
     */
    public function __construct(int $positionX, int $positionY, int $velocityX, int $velocityY)
    {
        $this->positionX = $positionX;
        $this->positionY = $positionY;
        $this->velocityX = $velocityX;
        $this->velocityY = $velocityY;
    }

    /**
     * @return int
     */
    public function getPositionX(): int
    {
        return $this->positionX;
    }

    /**
     * @param int $positionX
     */
    public function setPositionX(int $positionX): void
    {
        $this->positionX = $positionX;
    }

    /**
     * @return int
     */
    public function getPositionY(): int
    {
        return $this->positionY;
    }

    /**
     * @param int $positionY
     */
    public function setPositionY(int $positionY): void
    {
        $this->positionY = $positionY;
    }

    /**
     * @return int
     */
    public function getVelocityX(): int
    {
        return $this->velocityX;
    }

    /**
     * @param int $velocityX
     */
    public function setVelocityX(int $velocityX): void
    {
        $this->velocityX = $velocityX;
    }

    /**
     * @return int
     */
    public function getVelocityY(): int
    {
        return $this->velocityY;
    }

    /**
     * @param int $velocityY
     */
    public function setVelocityY(int $velocityY): void
    {
        $this->velocityY = $velocityY;
    }
}
