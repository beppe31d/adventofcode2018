<?php

namespace AdventOfCode\Device;

class Day22 extends AbstractDay
{
    public function exec(): void
    {
        echo 'Test: ' . $this->riskLevel(510, 10, 10);
        echo "\n\n";
        echo 'What is the total risk level for the smallest rectangle that includes 0,0 and the target\'s coordinates? '
            . $this->riskLevel(11739, 11, 718);
    }

    /**
     * @param int $depth
     * @param int $tx
     * @param int $ty
     * @return int
     */
    private function riskLevel(int $depth, int $tx, int $ty): int
    {
        $map = $this->buildMap($depth, $tx, $ty);

        return \array_sum(\array_map('array_sum', $map));
    }

    /**
     * @param int $depth
     * @param int $tx
     * @param int $ty
     * @return array
     */
    private function buildMap(int $depth, int $tx, int $ty): array
    {
        $erosionLevel = [];
        $map = [];
        $printableMap = [];
        for ($y = 0 ; $y <= $ty; $y++) {
            for ($x = 0 ; $x <= $tx; $x++) {
                $geologicIndex = $this->getRisk($erosionLevel, $tx, $ty, $x, $y);
                $erosionLevel[$y][$x] = (($geologicIndex + $depth) % 20183);
                $map[$y][$x] = $erosionLevel[$y][$x]  % 3;
                switch ($map[$y][$x]) {
                    case 0:
                        $printableMap[$y][$x] = '.';
                        break;
                    case 1:
                        $printableMap[$y][$x] = '=';
                        break;
                    default:
                        $printableMap[$y][$x] = '|';
                }
            }
        }

        $printableMap[0][0] = 'M';
        $printableMap[$ty][$tx] = 'T';
        echo $this->printMap($printableMap);

        return $map;
    }

    private function getRisk(array $map, int $tx, int $ty, $x, $y): float
    {
        if (($x === 0 && $y === 0) || ($x === $tx && $y === $ty)) {
            return 0;
        }

        if ($x === 0) {
            return $y * 48271;
        }

        if ($y === 0) {
            return $x * 16807;
        }

        return $map[$y - 1][$x] * $map[$y][$x - 1];;
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
