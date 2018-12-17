<?php

namespace AdventOfCode\Entity;

interface WarriorInterface
{
    public function getX(): int;
    public function getY(): int;
    public function getType(): string;
}
