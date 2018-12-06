<?php

namespace AdventOfCode\Device;

/**
 * Using only the Manhattan distance, determine the area around each coordinate by counting the number of integer X,Y
 * locations that are closest to that coordinate (and aren't tied in distance to any other coordinate).
 *
 * Your goal is to find the size of the largest area that isn't infinite.
 *
 * What is the size of the largest area that isn't infinite?
 */

/**
 * On the other hand, if the coordinates are safe, maybe the best you can do is try to find a region near as many
 * coordinates as possible.
 *
 * For example, suppose you want the sum of the Manhattan distance to all of the coordinates to be less than 32. For
 * each location, add up the distances to all of the given coordinates; if the total of those distances is less than 32,
 * that location is within the desired region.
 *
 * In particular, consider the highlighted location 4,3 located at the top middle of the region. Its calculation is as
 * follows, where abs() is the absolute value function:
 *
 * Distance to coordinate A: abs(4-1) + abs(3-1) =  5
 * Distance to coordinate B: abs(4-1) + abs(3-6) =  6
 * Distance to coordinate C: abs(4-8) + abs(3-3) =  4
 * Distance to coordinate D: abs(4-3) + abs(3-4) =  2
 * Distance to coordinate E: abs(4-5) + abs(3-5) =  3
 * Distance to coordinate F: abs(4-8) + abs(3-9) = 10
 * Total distance: 5 + 6 + 4 + 2 + 3 + 10 = 30
 * Because the total distance to all coordinates (30) is less than 32, the location is within the region.
 *
 * This region, which also includes coordinates D and E, has a total size of 16.
 *
 * Your actual region will need to be much larger than this example, though, instead including all locations with a
 * total distance of less than 10000.
 *
 * What is the size of the region containing all locations which have a total distance to all given coordinates of
 * less than 10000?
 */

/**
 * Class Day6
 * @package AdventOfCode\Device
 */
class Day6 extends AbstractDay
{
    public function exec(): void
    {
        $coordinates = $this->hydrateCoordinates();

//        echo 'What is the size of the largest area that isn\'t infinite? ' . $this->findLargestArea($coordinates);
        echo "\n\n";
        echo 'What is the size of the region containing all locations which have a total distance to all given 
        coordinates of less than 10000? ' . $this->findLargestAreaWithMaxDistance($coordinates, 10000);
    }

    /**
     * @param array $coordinates
     * @return int
     */
    private function findLargestArea(array $coordinates): int
    {
        [$minX, $minY, $maxX, $maxY] = $this->getBoundaries($coordinates);
        $countArea = [];
        $boundariesPerCoordinate = [];

        for ($row = $minY; $row <= $maxY; $row++) {
            echo 'Process row ' . $row . "\n";
            for ($column = $minX; $column <= $maxX; $column++) {
                $minDistance = null;
                $minDistanceKey = null;
                $countMinPoints = 1;
                foreach($coordinates as $key => [$x, $y]) {
                    $distance = $this->manhattanDistance($x, $y, $column, $row);

                    if ($minDistance === null || $distance <= $minDistance) {
                        if ($distance === $minDistance) {
                            $countMinPoints++;
                        } else {
                            $countMinPoints = 1;
                        }
                        $minDistance = $distance;
                        $minDistanceKey = $key;
                    }
                }

                if ($countMinPoints === 1) {
                    if (false === isset($countArea[$minDistanceKey])) {
                        $countArea[$minDistanceKey] = 0;
                        $boundariesPerCoordinate[$minDistanceKey]['minX'] = $column;
                        $boundariesPerCoordinate[$minDistanceKey]['minY'] = $row;
                        $boundariesPerCoordinate[$minDistanceKey]['maxX'] = $column;
                        $boundariesPerCoordinate[$minDistanceKey]['maxY'] = $row;
                    } else {
                        $boundariesPerCoordinate[$minDistanceKey]['minX'] =
                            \min([$boundariesPerCoordinate[$minDistanceKey]['minX'], $column]);
                        $boundariesPerCoordinate[$minDistanceKey]['minY'] =
                            \min([$boundariesPerCoordinate[$minDistanceKey]['minY'], $row]);
                        $boundariesPerCoordinate[$minDistanceKey]['maxX'] =
                            \max([$boundariesPerCoordinate[$minDistanceKey]['maxX'], $column]);
                        $boundariesPerCoordinate[$minDistanceKey]['maxY'] =
                            \max([$boundariesPerCoordinate[$minDistanceKey]['maxY'], $row]);
                    }
                    $countArea[$minDistanceKey]++;
                }
            }
        }

        foreach($coordinates as $key => [$x, $y]) {
            if ($x === $minX || $x === $maxX || $y === $minY || $y === $maxY ||
                $minX === $boundariesPerCoordinate[$key]['minX'] || $maxY === $boundariesPerCoordinate[$key]['maxX'] ||
                $minY === $boundariesPerCoordinate[$key]['minY'] || $maxY === $boundariesPerCoordinate[$key]['maxY']
            ) {
                unset($countArea[$key]);
            }
        }

        return \max($countArea);
    }

    /**
     * @param array $coordinates
     * @param int $maxDistance
     * @return int
     */
    private function findLargestAreaWithMaxDistance(array $coordinates, int $maxDistance): int
    {
        [$minX, $minY, $maxX, $maxY] = $this->getBoundaries($coordinates);
        $countSafePoints = 0;

        for ($row = $minY; $row <= $maxY; $row++) {
            echo 'Process row ' . $row . "\n";
            for ($column = $minX; $column <= $maxX; $column++) {
                $distance = 0;
                foreach ($coordinates as $key => [$x, $y]) {
                    $distance += $this->manhattanDistance($x, $y, $column, $row);
                    if ($distance >= $maxDistance) {
                        break;
                    }
                }
                if ($distance < $maxDistance) {
                    $countSafePoints++;
                }
            }
        }

        return $countSafePoints;
    }

    /**
     * @return array
     */
    private function hydrateCoordinates(): array
    {
        return \array_map(function($item) {
            [$x, $y] = explode(',', $item);
            return [(int)$x, (int)$y];
        }, $this->inputs);
    }

    /**
     * @param array $coordinates
     * @return array
     */
    private function getBoundaries(array $coordinates): array
    {
        $minX = null;
        $minY = null;
        $maxX = null;
        $maxY = null;

        foreach ($coordinates as $coordinate) {
            if ($minX === null || $minX > $coordinate[0]) {
                $minX = $coordinate[0];
            }
            if ($minY === null || $minY > $coordinate[1]) {
                $minY = $coordinate[1];
            }
            if ($maxX === null || $maxX < $coordinate[0]) {
                $maxX = $coordinate[0];
            }
            if ($maxY === null || $maxY < $coordinate[1]) {
                $maxY = $coordinate[1];
            }
        }

        return [$minX, $minY, $maxX, $maxY];
    }

    /**
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $y2
     * @return int
     */
    private function manhattanDistance(int $x1, int $y1, int $x2, int $y2): int
    {
        return \abs($x1 - $x2) + \abs($y1 - $y2);
    }
}
