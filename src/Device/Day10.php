<?php

namespace AdventOfCode\Device;

use AdventOfCode\Entity\PointOfLight;

/**
 * You can see these points of light floating in the distance, and record their position in the sky and their
 * velocity, the relative change in position per second (your puzzle input). The coordinates are all given from your
 * perspective; given enough time, those positions and velocities will move the points into a cohesive message!
 *
 * Rather than wait, you decide to fast-forward the process and calculate what the points will eventually spell.
 *
 * What message will eventually appear in the sky?
 */

/**
 * Class Day10
 * @package AdventOfCode\Device
 */
class Day10 extends AbstractDay
{
    public function exec(): void
    {
        $this->calculateMessage($this->hydratePoints());
        echo 'What message will eventually appear in the sky? see files output/day10-*.txt';
        echo "\n\n";
        echo 'The number in the file is the number of seconds';
    }

    /**
     * @param array $points
     */
    private function calculateMessage(array $points): void
    {
        $threshold = \count($points) * 0.25;
        for ($second = 0; $second < 12000; $second += $moves) {
            $x = [];
            $y = [];
            foreach ($points as $key => $point) {
                /** @var PointOfLight $point */
                $x[] = $point->getPositionX();
                $y[] = $point->getPositionY();
            }

            $minX = \min($x);
            $maxX = \max($x);
            $minY = \min($y);
            $maxY = \max($y);

            echo 'second: ' . $second . ' - min: ['. $minX . ', ' . $minY . '] - max[' . $maxX . ', ' . $maxY . "]\n";

            // Initially peed up with multiple moves.
            $moves = 10;
            if ($maxX - $minX <= $threshold || $maxY - $minY <= $threshold) {
                $moves = 1;
                $this->printPoints($points, $minX, $minY, $maxX, $maxY, $second);
            }

            foreach ($points as $key => $point) {
                /** @var PointOfLight $point */
                $point = $this->nextMove($point, $moves);
                $points[$key] = $point;
            }
        }
    }

    /**
     * @param array $points
     * @param int $minX
     * @param int $minY
     * @param int $maxX
     * @param int $maxY
     * @param int $second
     */
    private function printPoints(array $points, int $minX, int $minY, int $maxX, int $maxY, int $second): void
    {
        $row = $this->arrayFill($minX, $maxX - $minX + 1, '.');
        $matrix = $this->arrayFill($minY, $maxY - $minY + 1, $row);
        foreach ($points as $point) {
            /** @var PointOfLight $point */
            $matrix[$point->getPositionY()][$point->getPositionX()] = '#';
        }

        $f = fopen('output/day10-' . $second . '.txt', 'wb');
        foreach ($matrix as $row) {
            fwrite($f, implode('', $row) . "\n");
        }
        fclose($f);
    }

    /**
     * @param PointOfLight $point
     * @param int $moves
     * @return PointOfLight
     */
    private function nextMove(PointOfLight $point, int $moves = 1): PointOfLight
    {
        $point->setPositionX($point->getPositionX() + ($point->getVelocityX() * $moves));
        $point->setPositionY($point->getPositionY() + ($point->getVelocityY() * $moves));

        return $point;
    }

    /**
     * @return array
     */
    private function hydratePoints(): array
    {
        $points = [];
        foreach ($this->inputs as $input) {
            $input = \str_replace(' ', '', $input);
            $matches = [];
            preg_match(
                '/position=<(-?[0-9]\d*),(-?[0-9]\d*)>velocity=<(-?[0-9]\d*),(-?[0-9]\d*)>/',
                $input,
                $matches
            );
            $points[] = new PointOfLight($matches[1], $matches[2], $matches[3], $matches[4]);
        }

        return $points;
    }

    /**
     * \array_fill doesn't accept negative number.
     *
     * @param int $startIndex
     * @param int $numberElements
     * @param $value
     * @return array
     */
    private function arrayFill(int $startIndex, int $numberElements, $value): array
    {
        $filled = [];
        for ($i = 0; $i < $numberElements; $i++) {
            $filled[$startIndex + $i] = $value;
        }

        return $filled;
    }
}
