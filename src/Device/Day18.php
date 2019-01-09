<?php

namespace AdventOfCode\Device;

/**
 * @see https://adventofcode.com/2018/day/18
 */

/**
 * Class Day18
 * @package AdventOfCode\Device
 */
class Day18 extends AbstractDay
{
    public function exec(): void
    {
        $map = $this->getMap();
        echo 'What will the total resource value of the lumber collection area be after 10 minutes? '
            . $this->getTotalResource($map, 10);

        // With the commented code in getMapAfterMinutes I found the map repeats every 28 minutes after minute 455.
        echo 'What will the total resource value of the lumber collection area be after 1000000000 minutes?'
            . $this->getTotalResource($map, (1000000000 - 455) % 28 + 455);
    }

    private function getTotalResource(array $map, $minutes): int
    {
        $map = $this->getMapAfterMinutes($map, $minutes);
        $trees = 0;
        $lumberyards = 0;
        foreach ($map as $row) {
            $text = \implode('', $row);
            $trees += \substr_count($text, '|');
            $lumberyards += \substr_count($text, '#');
        }

        return $trees * $lumberyards;
    }

    /**
     * @param int $minutes
     * @return array
     */
    private function getMapAfterMinutes(array $map, int $minutes): array
    {
//        $codes = [];
        for ($i = 1; $i <= $minutes; $i++) {
            $map = $this->nextMinute($map);
//            echo $i . "\n";
//            echo $this->printMap($map);
//            $hash = \md5(\serialize($map));
//            if (false === isset($codes[$hash])) {
//                $codes[$hash] = $i;
//            } else {
//                echo $codes[$hash] . ' ' . $i . "\n";
//                return $map;
//            }

        }

//        $codes = \array_filter($codes, function ($item) {
//            return $item > 20;
//        });
//        foreach ($codes as $key => $value) {
//            echo $key . ' ' . $value . "\n";
//        }

        return $map;
    }

    /**
     * @param array $map
     * @return array
     */
    private function nextMinute(array $map): array
    {
        $newMap = $map;

        foreach ($map as $rowKey => $row) {
            foreach ($row as $colKey => $value) {
                $neighbors = $this->getNeighbors($map, $colKey, $rowKey);
                if ($value === '.' && \substr_count($neighbors, '|') >= 3) {
                    $newMap[$rowKey][$colKey] = '|';
                    continue;
                }
                if ($value === '|' && \substr_count($neighbors, '#') >= 3) {
                    $newMap[$rowKey][$colKey] = '#';
                    continue;
                }
                if ($value === '#' &&
                    (\substr_count($neighbors, '|') < 1 || \substr_count($neighbors, '#') < 1)) {
                    $newMap[$rowKey][$colKey] = '.';
                }
            }
        }

        return $newMap;
    }

    /**
     * @param array $map
     * @param int $x
     * @param int $y
     * @return string
     */
    private function getNeighbors(array $map, int $x, int $y): string
    {
        return \implode('', [
            $map[$y - 1][$x - 1] ?? '',
            $map[$y - 1][$x] ?? '',
            $map[$y - 1][$x + 1] ?? '',
            $map[$y][$x - 1] ?? '',
            $map[$y][$x + 1] ?? '',
            $map[$y + 1][$x - 1] ?? '',
            $map[$y + 1][$x] ?? '',
            $map[$y + 1][$x + 1] ?? '',
        ]);
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
