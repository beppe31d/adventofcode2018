<?php

namespace AdventOfCode\Device;

/**
 * @see https://adventofcode.com/2018/day/17
 */

use AdventOfCode\Entity\WaterPoint;

/**
 * Class Day17
 * @package AdventOfCode\Device
 */
class Day17 extends AbstractDay
{
    public function exec(): void
    {
        $map = $this->buildMap();
        $this->waterFlow($map);
    }

    /**
     * @param array $map
     * @return int
     */
    private function waterFlow(array $map): void
    {
        $point = new WaterPoint(500, 0, null);
        $map[$point->getY()][$point->getX()] = '+';

        while ($point !== null) {
            $nextPoint = $this->getNextPoint($point, $map);
//            $this->printMap($map);
            if (null !== $nextPoint) {
                $point = $nextPoint;
                $map[$point->getY()][$point->getX()] = '|';
            } elseif ($point->getPrev() !== null) {
                $map[$point->getY()][$point->getX()] =
                    false === $this->isOutOfBound($point, $map) && true === $this->hasGround($point, $map)
                        ? '~' : '|';

                $point->getPrev()->setBoundaries($point->hasBoundaries());

                // No more moves, come back. If prev is null exit.
                $point = $point->getPrev();
            } else {
                $point = null;
            }
        }

        $map = $this->fixTiles($map);
        $sum = 0;
        $sumTiles = 0;
        $startCount = false;
        foreach ($map as $row) {
            $text = \implode('', $row);
            if ($startCount === false && false !== \strpos($text, '#')) {
                $startCount = true;
            }
            if (true === $startCount) {
                $sum += \substr_count($text, '|') + \substr_count($text, '~');
                $sumTiles += \substr_count($text, '~');
            }
        }

        \file_put_contents('test.txt', $this->printMap($map));

        echo 'How many tiles can the water reach within the range of y values in your scan? ' . $sum;
        echo "\n\n";
        echo 'How many water tiles are left after the water spring stops producing water and all remaining water not at 
        rest has drained? ' . $sumTiles;
    }

    /**
     * @param array $map
     * @return array
     */
    private function fixTiles(array $map): array
    {
        $newMap = $map;
        foreach ($newMap as $rowKey => $row) {
            foreach ($row as $colKey => $column) {
                if ($column === '|' && $newMap[$rowKey][$colKey - 1] === '~') {
                    $fixBack = 1;
                    while ($newMap[$rowKey][$colKey - $fixBack] === '~') {
                        $newMap[$rowKey][$colKey - $fixBack] = '|';
                        $fixBack++;
                    }
                }
                if ($column === '|' && true === isset($newMap[$rowKey][$colKey + 1])
                    && $newMap[$rowKey][$colKey + 1] === '~') {
                    $fixBack = 1;
                    while ($newMap[$rowKey][$colKey + $fixBack] === '~') {
                        $newMap[$rowKey][$colKey + $fixBack] = '|';
                        $fixBack++;
                    }
                }
            }
        }

        return $newMap;
    }

    /**
     * @param WaterPoint $point
     * @param array $map
     * @return WaterPoint|null
     */
    public function getNextPoint(WaterPoint $point, array $map): ?WaterPoint
    {
        $x = null;
        $y = null;

        if (true === $this->isOutOfBound($point, $map)) {
            $point->setBoundaries(false);
            return null;
        }

        if ($map[$point->getY() + 1][$point->getX()] === '.') {
            $x = $point->getX();
            $y = $point->getY() + 1;
        } elseif (true === $this->hasGround($point, $map)) {
            if (isset($map[$point->getY()][$point->getX() - 1]) && $map[$point->getY()][$point->getX() - 1] === '.') {
                $x = $point->getX() - 1;
                $y = $point->getY();
            } elseif (isset($map[$point->getY()][$point->getX() + 1])
                && $map[$point->getY()][$point->getX() + 1] === '.') {
                $x = $point->getX() + 1;
                $y = $point->getY();
            }
        }

        if ($x === null) {
            return null;
        }

        return new WaterPoint($x, $y, $point);
    }

    /**
     * @param WaterPoint $point
     * @param array $map
     * @return bool
     */
    private function isOutOfBound(WaterPoint $point, array $map): bool
    {
        return $point->getY() + 1 > \max(\array_keys($map));
    }

    /**
     * @param WaterPoint $point
     * @param array $map
     * @return bool
     */
    public function hasGround(WaterPoint $point, array $map): bool
    {
        if (true === \in_array($map[$point->getY() + 1][$point->getX()], ['.', '|'], true)) {
            return false;
        }

        if ($map[$point->getY() + 1][$point->getX() - 1] === '|' ||
            $map[$point->getY() + 1][$point->getX() + 1] === '|') {
            return false;
        }

        if ($map[$point->getY()][$point->getX() - 1] === '|' &&
            $map[$point->getY()][$point->getX() + 1] === '|' &&
            true === \in_array($map[$point->getY() + 1][$point->getX()], ['#', '~'], true) &&
            true === \in_array($map[$point->getY() + 1][$point->getX() - 1], ['#', '~'], true) &&
            true === \in_array($map[$point->getY() + 1][$point->getX() + 1], ['#', '~'], true)
        ) {
            return false;
        }

        if ($map[$point->getY()][$point->getX() - 1] === '.' &&
            $map[$point->getY()][$point->getX() + 1] === '.' &&
            $map[$point->getY() + 1][$point->getX() - 1] === '~' &&
            $map[$point->getY() + 1][$point->getX()] === '~' &&
            $map[$point->getY() + 1][$point->getX() + 1] === '~'
        ) {
            for ($i = $point->getX() + 1; $i < $point->getX() + 200; $i++) {
                if ($map[$point->getY() + 1][$i] === '.') {
                    return false;
                }
                if ($map[$point->getY() + 1][$i] === '#') {
                    break;
                }
            }
            for ($i = $point->getX() - 1; $i > $point->getX() - 200; $i--) {
                if ($map[$point->getY() + 1][$i] === '.') {
                    return false;
                }
                if ($map[$point->getY() + 1][$i] === '#') {
                    break;
                }
            }
        }

        if (true === \in_array($map[$point->getY() + 1][$point->getX()], ['#', '~'], true) &&
            true === \in_array($map[$point->getY()][$point->getX() - 1], ['#', '~', '|'], true) &&
            true === \in_array($map[$point->getY()][$point->getX() + 1], ['.', '|', '#'], true)
        ) {
            return true;
        }

        if (true === \in_array($map[$point->getY() + 1][$point->getX()], ['#', '~'], true) &&
            true === \in_array($map[$point->getY()][$point->getX() + 1], ['#', '~', '|'], true) &&
            true === \in_array($map[$point->getY()][$point->getX() - 1], ['.', '|', '#'], true)
        ) {
            return true;
        }

        $boundaries = (true === $point->hasBoundaries() &&
            \in_array($map[$point->getY() + 1][$point->getX()], ['#', '~', '|'], true)
        );

        return $boundaries;
    }

    /**
     * @return array
     */
    private function buildMap(): array
    {
        $points = [];
        foreach ($this->inputs as $input) {
            $input = \str_replace("\n", '', $input);
            \preg_match('/(x|y)=([\d]+), (x|y)=([\d]+)..([\d]+)/', $input, $matches);
            [, $var1, $minVar1, $var2, $minVar2, $maxVar2] = $matches;
            $points[] = [
                $var1 => [
                    $minVar1,
                    $minVar1
                ],
                $var2 => [
                    $minVar2,
                    $maxVar2
                ],
            ];
        }

        $minX = null;
        $maxX = null;
        $minY = 0;
        $maxY = null;

        foreach ($points as $point) {
            if ($minX === null || $point['x'][0] < $minX) {
                $minX = $point['x'][0];
            }
            if ($maxX === null || $point['x'][1] > $maxX) {
                $maxX = $point['x'][1];
            }
            if ($maxY === null || $point['y'][1] > $maxY) {
                $maxY = $point['y'][1];
            }
        }

        $row = \array_fill($minX - 1, $maxX - $minX + 3, '.');
        $map = \array_fill($minY, $maxY - $minY + 1, $row);

        foreach ($points as $point) {
            for ($row = $point['y'][0]; $row <= $point['y'][1]; $row++) {
                for ($column = $point['x'][0]; $column <= $point['x'][1]; $column++) {
                    $map[$row][$column] = '#';
                }
            }
        }

        return $map;
    }

    /**
     * @param array $map
     * @return string
     */
    private function printMap(array $map): string
    {
        $text = '';
        foreach ($map as $row) {
            $text .= implode('', $row) . "\n";
        }

        $text .= "\n\n";

        return $text;
    }
}
