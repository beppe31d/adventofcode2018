<?php

namespace AdventOfCode\Entity;

class Marble
{
    /** @var int */
    private $value;

    /** @var int */
    private $prev;

    /** @var int */
    private $next;

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     */
    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getPrev(): int
    {
        return $this->prev;
    }

    /**
     * @param int $prev
     */
    public function setPrev(int $prev): void
    {
        $this->prev = $prev;
    }

    /**
     * @return int
     */
    public function getNext(): int
    {
        return $this->next;
    }

    /**
     * @param int $next
     */
    public function setNext(int $next): void
    {
        $this->next = $next;
    }
}
