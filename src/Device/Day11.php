<?php

namespace AdventOfCode\Device;

/**
 * @see https://adventofcode.com/2018/day/11
 *
 * Formula: https://en.wikipedia.org/wiki/Summed-area_table
 */

/**
 * Class Day11
 * @package AdventOfCode\Device
 */
class Day11 extends AbstractDay
{
    /** @var int */
    private const GRID_DIMENSION = 300;

    /** @var int */
    private const SERIAL_NUMBER = 8561;

    /** @var array */
    private $sumMatrix;

    public function exec(): void
    {
        $summedAreaTable = $this->populateSumMatrix($this->populatePowerLevelMatrix());
        echo 'What is the X,Y coordinate of the top-left fuel cell of the 3x3 square with the largest total power? '
            . $this->getCornerWithMaxFuel($summedAreaTable);
        echo "\n\n";
        echo 'What is the X,Y,size identifier of the square with the largest total power? '
            . $this->getDimensionAndCornerWithMaxFuel($summedAreaTable);
    }

    /**
     * @param array $summedAreaTable
     * @return string
     */
    private function getCornerWithMaxFuel(array $summedAreaTable): string
    {
        [$maxX, $maxY] =
            $this->getCoordinatesFromMatrix($this->populateGroupedPowerLevel($summedAreaTable, 3));

        return implode(',' , [$maxX, $maxY]);
    }

    /**
     * @param array $summedAreaTable
     * @return string
     */
    private function getDimensionAndCornerWithMaxFuel(array $summedAreaTable): string
    {
        $squareDimension = null;
        $x = null;
        $y = null;
        $maxValueAllSquare = 0;

        for ($size = 1; $size <= self::GRID_DIMENSION; $size++) {
            [$maxX, $maxY, $maxValue] =
                $this->getCoordinatesFromMatrix($this->populateGroupedPowerLevel($summedAreaTable, $size));

            if ($maxValue > $maxValueAllSquare) {
                $squareDimension = $size;
                $maxValueAllSquare = $maxValue;
                $x = $maxX;
                $y = $maxY;
            }
        }



        return implode(',', [$x, $y, $squareDimension]);
    }

    /**
     * @param array $summedAreaTable
     * @param int $squareSize
     * @return array
     */
    private function populateGroupedPowerLevel(array $summedAreaTable, int $squareSize): array
    {
        $groupedPowerLevel = $this->getBaseMatrix();

        for ($y = 1; $y <= self::GRID_DIMENSION; $y++) {
            for ($x = 1; $x <= self::GRID_DIMENSION; $x++) {
                if (isset($summedAreaTable[$y + $squareSize][$x], $summedAreaTable[$y][$x + $squareSize],
                    $summedAreaTable[$y + $squareSize][$x + $squareSize])) {
                    $groupedPowerLevel[$y][$x] = $summedAreaTable[$y + $squareSize][$x + $squareSize] +
                        $summedAreaTable[$y][$x] - $summedAreaTable[$y + $squareSize][$x]
                        - $summedAreaTable[$y][$x + $squareSize];
                }
            }
        }

        return $groupedPowerLevel;
    }

    /**
     * @param array $powerLevel
     * @return array
     */
    private function populateSumMatrix(array $powerLevel): array
    {
        for ($y = 1; $y <= self::GRID_DIMENSION; $y++) {
            for ($x = 1; $x <= self::GRID_DIMENSION; $x++) {
                if (false === isset($this->sumMatrix[$y][$x])) {
                    $this->calculateCellSize($powerLevel, $x, $y);
                }
            }
        }

        return $this->sumMatrix;
    }

    /**
     * @param array $powerLevel
     * @param int $x
     * @param int $y
     */
    private function calculateCellSize(array $powerLevel, int $x, int $y): void
    {
        if ($y - 1 > 0 && false === isset($this->sumMatrix[$y - 1][$x])) {
            $this->calculateCellSize($powerLevel, $x, $y - 1);
        }
        if ($x - 1 > 0 && false === isset($this->sumMatrix[$y][$x - 1])) {
            $this->calculateCellSize($powerLevel, $x - 1, $y);
        }
        if ($y - 1 > 0 && $x - 1 > 0 && false === isset($this->sumMatrix[$y - 1][$x - 1])) {
            $this->calculateCellSize($powerLevel, $x - 1, $y - 1);
        }

        $this->sumMatrix[$y][$x] = $powerLevel[$y][$x] +
            ($y - 1 > 0 ? $this->sumMatrix[$y - 1][$x] : 0) +
            ($x - 1 > 0 ? $this->sumMatrix[$y][$x - 1] : 0) -
            ($x - 1 > 0 && $y - 1 > 0 ? $this->sumMatrix[$y - 1][$x - 1] : 0);
    }

    /**
     * @param array $groupedPowerLevel
     * @return array
     */
    private function getCoordinatesFromMatrix(array $groupedPowerLevel): array
    {
        $maxX = [];
        for ($y = 1; $y <= self::GRID_DIMENSION; $y++) {
            $maxX[$y] = \max($groupedPowerLevel[$y]);
        }

        $max = \max($maxX);
        $maxY = \array_keys($maxX, $max)[0];
        $maxX = \array_keys($groupedPowerLevel[$maxY], \max($groupedPowerLevel[$maxY]))[0];

        // TODO The results are all an index behind the right answer.
        $maxX++;
        $maxY++;

        return [$maxX, $maxY, $max];
    }

    /**
     * @return array
     */
    private function getBaseMatrix(): array
    {
        $row = \array_fill(1, self::GRID_DIMENSION, 0);

        return \array_fill(1, self::GRID_DIMENSION, $row);
    }

    /**
     * @return array
     */
    private function populatePowerLevelMatrix(): array
    {
        $powerLevel = [];
        for ($y = 1; $y <= self::GRID_DIMENSION; $y++) {
            for ($x = 1; $x <= self::GRID_DIMENSION; $x++) {
                $powerLevel[$y][$x] = $this->powerLevel($x, $y);
            }
        }

        return $powerLevel;
    }

    /**
     * @param int $x
     * @param int $y
     * @return int
     */
    private function powerLevel(int $x, int $y): int
    {
        $rackId = $x + 10;
        $powerLevel = (int) floor(((($rackId * $y) + self::SERIAL_NUMBER) * $rackId) / 100);

        return \substr($powerLevel, -1) - 5;
    }
}
