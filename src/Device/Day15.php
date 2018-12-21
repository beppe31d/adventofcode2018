<?php

namespace AdventOfCode\Device;

use AdventOfCode\Entity\Warrior;
use AdventOfCode\Entity\WarriorDistance;
use AdventOfCode\Entity\WarriorInterface;

/**
 * Class Day15
 * @package AdventOfCode\Device
 */
class Day15 extends AbstractDay
{
    // For part 1 set to 3.
    private const ELVES_ATTACK = 16;

    public function exec(): void
    {
        echo 'What is the outcome of the combat? ' . $this->getCombatResult();
    }

    /**
     * @return int
     */
    private function getCombatResult(): int
    {
        $map = $this->getMap();
        $map = $this->populatePoints($map);
        $warriors = $this->populateWarriors($map);
        $totalElves = $this->filterWarriors($warriors, 'E');

//        $this->printMap($map, 0, $warriors);
        for ($round = 1; $round < 1000; $round++) {
            [$map, $warriors] = $this->round($map, $warriors);

            $elves = $this->filterWarriors($warriors, 'E');
            $goblins = $this->filterWarriors($warriors, 'G');

//            $this->printMap($map, $round + 1, $warriors);
            if (true === empty($elves) || true === empty($goblins)) {
                $hp = 0;
                foreach (\array_merge($elves, $goblins) as $warrior) {
                    /** @var Warrior $warrior */
                    $hp += $warrior->getHp();
                }

                echo 'Elves percentage: ' . \count($elves) / \count($totalElves) . "\n\n";

                return $hp * $round;
            }
        }

        return 0;
    }

    /**
     * @param array $warriors
     * @param string $type
     * @return array
     */
    private function filterWarriors(array $warriors, string $type): array
    {
        return \array_filter($warriors, function(Warrior $warrior) use ($type) {
            return $warrior->getType() === $type && $warrior->getHp() > 0;
        });
    }

    /**
     * @param array $map
     * @param array $warriors
     * @return array
     */
    private function round(array $map, array $warriors): array
    {
        $newWarriors = [];
        foreach ($warriors as $warrior) {
            if ($warrior instanceof Warrior) {
                if ($warrior->getHp() <= 0) {
                    continue;
                }
                /** @var Warrior $warrior */
                $neighbor = $this->getNeighbor($warrior, $map);
                if ($neighbor !== null) {
                    $warrior->attack($neighbor);
                    if ($warrior->getHp() <= 0) {
                        $map[$warrior->getY()][$warrior->getX()] = '.';
                    } else {
                        $newWarriors[$warrior->getX() . ',' . $warrior->getY()] = $warrior;
                    }
                    if ($neighbor->getHp() <= 0) {
                        $map[$neighbor->getY()][$neighbor->getX()] = '.';
                        unset($warriors[$neighbor->getX() . ',' . $neighbor->getY()]);
                    }
                } else {
                    $map[$warrior->getY()][$warrior->getX()] = '.';

                    $warriorDistance = $this->move($map, $warrior);

                    if ($warriorDistance !== null) {
                        $warrior->setX($warriorDistance->getX());
                        $warrior->setY($warriorDistance->getY());
                        $neighbor = $this->getNeighbor($warrior, $map);
                    }
                    $map[$warrior->getY()][$warrior->getX()] = $warrior;
                    $newWarriors[$warrior->getX() . ',' . $warrior->getY()] = $warrior;

                    if ($warriorDistance !== null && $neighbor !== null) {
                        $warrior->attack($neighbor);
                        if ($warrior->getHp() <= 0) {
                            $map[$warrior->getY()][$warrior->getX()] = '.';
                        } else {
                            $newWarriors[$warrior->getX() . ',' . $warrior->getY()] = $warrior;
                        }
                        if ($neighbor->getHp() <= 0) {
                            $map[$neighbor->getY()][$neighbor->getX()] = '.';
                            unset($warriors[$neighbor->getX() . ',' . $neighbor->getY()]);
                        }
                    }
                }
            }
        }

        return [$map, $this->sortWarriors($newWarriors)];
    }

    /**
     * @param array $map
     * @param WarriorInterface $warrior
     * @return Warrior
     */
    private function move(array $map, WarriorInterface $warrior): ?WarriorInterface
    {
        $distanceMap = $map;
        $warriorDistance = new WarriorDistance($warrior->getX(), $warrior->getY(), $warrior->getType(), null);
        $distanceMap[$warriorDistance->getY()][$warriorDistance->getX()] = 0;
        $nextIterationPoints = [$warriorDistance];

        for ($distance = 1; $distance < 100; $distance++) {
            $iterationPoints = $nextIterationPoints;
            $nextIterationPoints = [];

            foreach ($iterationPoints as $warriorDistance) {
                /** @var WarriorDistance $warriorDistance */
                if ($this->isEnemy($warriorDistance, $map, $warriorDistance->getX(), $warriorDistance->getY() - 1)
                    || $this->isEnemy($warriorDistance, $map, $warriorDistance->getX(), $warriorDistance->getY() + 1)
                    || $this->isEnemy($warriorDistance, $map, $warriorDistance->getX() - 1, $warriorDistance->getY())
                    || $this->isEnemy($warriorDistance, $map, $warriorDistance->getX() + 1, $warriorDistance->getY())
                ) {
                    while (true) {
                        if ($warriorDistance->getPrev() === null) {
                            return $warriorDistance;
                        }
                        $warriorDistance = $warriorDistance->getPrev();
                    }
                }

                $prev = $distance > 1 ? $warriorDistance : null;
                $y = $warriorDistance->getY() - 1;
                $x = $warriorDistance->getX();
                if (true ===
                    $this->isCellFree($distanceMap, $warriorDistance, $x, $y)
                ) {
                    $nextIterationPoints[] =
                        new WarriorDistance(
                            $x,
                            $y,
                            $warriorDistance->getType(),
                            $prev
                        );
                    $distanceMap[$y][$x] = $distance;
                }

                $y = $warriorDistance->getY();
                $x = $warriorDistance->getX() - 1;
                if (true ===
                    $this->isCellFree($distanceMap, $warriorDistance, $x, $y)
                ) {
                    $nextIterationPoints[] =
                        new WarriorDistance(
                            $x,
                            $y,
                            $warriorDistance->getType(),
                            $prev
                        );
                    $distanceMap[$y][$x] = $distance;
                }

                $y = $warriorDistance->getY();
                $x = $warriorDistance->getX() + 1;
                if (true ===
                    $this->isCellFree($distanceMap, $warriorDistance, $x, $y)
                ) {
                    $nextIterationPoints[] =
                        new WarriorDistance(
                            $x,
                            $y,
                            $warriorDistance->getType(),
                            $prev
                        );
                    $distanceMap[$y][$x] = $distance;
                }

                $y = $warriorDistance->getY() + 1;
                $x = $warriorDistance->getX();
                if (true ===
                    $this->isCellFree($distanceMap, $warriorDistance, $x, $y)
                ) {
                    $nextIterationPoints[] =
                        new WarriorDistance(
                            $x,
                            $y,
                            $warriorDistance->getType(),
                            $prev
                        );
                    $distanceMap[$y][$x] = $distance;
                }
            }
        }

        return null;
    }

    /**
     * @param array $map
     * @param WarriorDistance $warriorDistance
     * @param int $x
     * @param int $y
     * @return bool
     */
    private function isCellFree(array $map, WarriorDistance $warriorDistance, int $x, int $y): bool
    {
        return $map[$y][$x] === '.'
            && ($warriorDistance->getPrev() === null ||
                $y !== $warriorDistance->getPrev()->getY() ||
                $x !== $warriorDistance->getPrev()->getX()
            );
    }

    /**
     * @param Warrior $warrior
     * @param array $map
     * @return array|null
     */
    private function getNeighbor(Warrior $warrior, array $map): ?Warrior
    {
        $enemies = [];

        if (true === $this->isEnemy($warrior, $map, $warrior->getX(), $warrior->getY() - 1)) {
            $enemies[] = $map[$warrior->getY() - 1][$warrior->getX()];
        }
        if (true === $this->isEnemy($warrior, $map, $warrior->getX() - 1, $warrior->getY())) {
            $enemies[] = $map[$warrior->getY()][$warrior->getX() - 1];
        }
        if (true === $this->isEnemy($warrior, $map, $warrior->getX() + 1, $warrior->getY())) {
            $enemies[] = $map[$warrior->getY()][$warrior->getX() + 1];
        }
        if (true === $this->isEnemy($warrior, $map, $warrior->getX(), $warrior->getY() + 1)) {
            $enemies[] = $map[$warrior->getY() + 1][$warrior->getX()];
        }

        if (true === empty($enemies)) {
            return null;
        }

        \usort($enemies, function (Warrior $a, Warrior $b) {
            return $a->getHp() > $b->getHp();
        });

        return $enemies[0];
    }

    /**
     * @param WarriorInterface $warrior
     * @param array $map
     * @param int $x
     * @param int $y
     * @return bool
     */
    private function isEnemy(WarriorInterface $warrior, array $map, int $x, int $y): bool
    {
        return true === isset($map[$warrior->getY() - 1][$warrior->getX()]) &&
        $map[$y][$x] instanceof Warrior &&
        $warrior->getType() !== $map[$y][$x]->getType();
    }

    /**
     * @param array $warriors
     * @return array
     */
    private function sortWarriors(array $warriors): array
    {
        \usort($warriors, function($a, $b) {
            /** @var Warrior $a */
            /** @var Warrior $b */
            return ($a->getY() > $b->getY()) || ($a->getY() === $b->getY() && $a->getX() > $b->getX());
        });

        $result = [];
        foreach ($warriors as $warrior) {
            /** @var Warrior $warrior */
            $result[$warrior->getX() . ',' . $warrior->getY()] = $warrior;
        }

        return $warriors;
    }

    /**
     * @param array $map
     * @param int $round
     * @param array $warriors
     */
    private function printMap(array $map, int $round, array $warriors): void
    {
        echo 'Round ' . $round . "\n";
        foreach ($warriors as $key => $warrior) {
            /** @var Warrior $warrior */
            echo $key . ' ' . $warrior->getType() . ' ' . $warrior->getHp() . "\n";
        }
        foreach ($map as $row) {
            echo implode('', $row) . "\n";
        }

        echo "\n\n";
    }

    /**
     * @param array $map
     * @return array
     */
    private function populateWarriors(array $map): array
    {
        $warriors = [];
        foreach ($map as $keyRow => $row) {
            foreach ($row as $keyCol => $value) {
                if ($value instanceof Warrior) {
                    $warriors[$value->getX() . '-' . $value->getY()] = $value;
                }
            }
        }

        return $warriors;
    }

    /**
     * @param array $map
     * @return array
     */
    private function populatePoints(array $map): array
    {
        $warriors = [];
        foreach ($map as $keyRow => $row) {
            foreach ($row as $keyCol => $value) {
                if (true === \in_array($value, ['G', 'E'], true)) {
                    $attack = $value === 'E' ? self::ELVES_ATTACK : 3;
                    $warriors[$keyRow][$keyCol] = new Warrior($keyCol, $keyRow, $value, $attack);
                } else {
                    $warriors[$keyRow][$keyCol] = $map[$keyRow][$keyCol];
                }
            }
        }

        return $warriors;
    }
}
