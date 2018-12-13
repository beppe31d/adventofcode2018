<?php

namespace AdventOfCode\Device;

/**
 * @see https://adventofcode.com/2018/day/13
 */

use AdventOfCode\Entity\Cart;

/**
 * Class Day13
 * @package AdventOfCode\Device
 */
class Day13 extends AbstractDay
{
    public function exec(): void
    {
        echo 'Location of first crash: ' . $this->findFirstCrash();
        echo "\n\n";
        echo 'What is the location of the last cart at the end of the first tick where it is the only cart left? '
            . $this->findLastCart();
    }

    /**
     * @return string
     */
    private function findFirstCrash(): string
    {
        $map = $this->getMap();
        [$map, $points] = $this->populatePoints($map);
//        $this->printMap($map, $points);
        for ($i = 0; $i < 1000; $i++) {
            $points = $this->tick($map, $points);
//            $this->printMap($map, $points);
            $collide = $this->pointsCollide($points);
            if (null !== $collide) {
                return $collide;
            }
        }

        return '';
    }

    /**
     * @return string
     */
    private function findLastCart(): string
    {
        $map = $this->getMap();
        [$map, $points] = $this->populatePoints($map);
        while (true) {
            $points = $this->tick($map, $points);
            $points = $this->filterPointsThatCollide($points);
            if (\count($points) === 1) {
                /** @var Cart $point */
                $point = $points[0];
                return $point->getX() . ',' . $point->getY();
            }
        }
    }

    /**
     * @param array $points
     * @return array
     */
    private function filterPointsThatCollide(array $points): array
    {
        $newPoints = [];

        $prevPoint = null;
        foreach ($points as $point) {
            /** @var Cart $point */
            if ($prevPoint !== null && true === $point->equals($prevPoint)) {
                $prevPoint = null;
                continue;
            }
            if ($prevPoint !== null) {
                $newPoints[] = $prevPoint;
            }
            $prevPoint = $point;
        }

        if ($prevPoint !== null) {
            $newPoints[] = $prevPoint;
        }

        return $newPoints;
    }

    /**
     * @param array $points
     * @return string
     */
    private function pointsCollide(array $points): ?string
    {
        $prevPoint = null;
        foreach ($points as $point) {
            /** @var Cart $point */
            if ($prevPoint !== null && true === $point->equals($prevPoint)) {
                return $point->getX() . ',' . $point->getY();
            }
            $prevPoint = $point;
        }

        return null;
    }

    /**
     * @param array $map
     * @param array $points
     * @return array
     */
    private function tick(array $map, array $points): array
    {
        $newPoints = [];
        $currentPoint = null;

        foreach ($points as $point) {
            /** @var Cart $point */
            // If points collide the second point won't move.
            if ($currentPoint !== null && true === $point->equals($currentPoint)) {
                $newPoints[] = $point;
                continue;
            }

            $point->tick();
            if (true === \in_array($map[$point->getY()][$point->getX()], ['/', '\\', '+'], true)) {
                $point->changeDirection($map[$point->getY()][$point->getX()]);
            }

            $newPoints[] = $point;
            $currentPoint = $point;
        }


        return $this->sortPoints($newPoints);
    }

    /**
     * @param array $points
     * @return array
     */
    private function sortPoints(array $points): array
    {
        \usort($points, function($a, $b) {
           /** @var Cart $a */
           /** @var Cart $b */
           return ($a->getY() > $b->getY()) || ($a->getY() === $b->getY() && $a->getX() > $b->getX());
        });

        return $points;
    }

    /**
     * @param array $map
     * @param array $points
     */
    private function printMap(array $map, array $points): void
    {
        foreach($points as $point) {
            /** @var Cart $point */
            $map[$point->getY()][$point->getX()] = $point->getDirection();
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
    private function populatePoints(array $map): array
    {
        $points = [];
        foreach ($map as $keyRow => $row) {
            foreach ($row as $keyCol => $value) {
                if (true === \in_array($value, ['^', '>', 'v', '<'], true)) {
                    $points[] = new Cart($keyCol, $keyRow, $value);
                    $map[$keyRow][$keyCol] = $value === '^' || $value === 'v' ? '|' : '-';
                }
            }
        }

        return [$map, $points];
    }

    /**
     * @return array
     */
    private function getMap(): array
    {
        $map = [];
        foreach($this->inputs as $input) {
            $map[] = \str_split(\str_replace("\n", '', $input));
        }

        return $map;
    }
}
