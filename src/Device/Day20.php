<?php

namespace AdventOfCode\Device;

/**
 * @see https://adventofcode.com/2018/day/20
 */

/**
 * Class Day20
 * @package AdventOfCode\Device
 */
class Day20 extends AbstractDay
{
    /** @var array */
    private $steps;

    public function exec(): void
    {
//        echo 'What is the largest number of doors you would be required to pass through to reach a room? '
//            . $this->part1();
        echo "\n\n";
        echo 'How many rooms have a shortest path from your current location that pass through at least 1000 doors? '
            . $this->part2();
    }

    /**
     * @return int
     */
    public function part1(): int
    {
        $map = $this->buildMap(\str_replace(['^', '$', "\n"], '', $this->inputs[0]), [], 0, 0);

        return $this->findLongestPath($map, 0, 0);
    }

    public function part2(): int
    {
        // Not working :(
        $map = $this->buildMap(\str_replace(['^', '$', "\n"], '', $this->inputs[0]), [], 0, 0);
        $paths = $this->findAllPath($map, 0, 0);

        return \count(\array_filter($paths, function ($item) {
            return $item >= 1000;
        }));
    }

    /**
     * @param array $map
     * @param int $x
     * @param int $y
     * @param int|null $oldX
     * @param int|null $oldY
     * @param int $step
     * @return array
     */
    private function findAllPath(array $map, int $x, int $y, int $oldX = null, int $oldY = null, $step = 0): array
    {
        $paths = $this->findPaths($map, $x, $y, $oldX, $oldY);

        $key = $y . '_' . $x;
        if (false === isset($this->steps[$key]) || $this->steps[$key] > $step) {
            $this->steps[$key] = $step;
        }

        if (\count($paths) === 0) {
            return $this->steps;
        }

        $step++;

        foreach($paths as $path) {
            $this->findAllPath($map, $path[0], $path[1], $x, $y, $step);
        }

        return $this->steps;
    }

    /**
     * @param array $map
     * @param int $x
     * @param int $y
     * @param int|null $oldX
     * @param int|null $oldY
     * @return int
     */
    private function findLongestPath(array $map, int $x, int $y, int $oldX = null, int $oldY = null): int
    {
        $paths = $this->findPaths($map, $x, $y, $oldX, $oldY);

        if (\count($paths) === 0) {
            return 0;
        }

        $steps = 1;
        $additionalSteps = [];
        foreach($paths as $path) {
            $additionalSteps[] = $this->findLongestPath($map, $path[0], $path[1], $x, $y);
        }

        $steps += \max($additionalSteps);

        return $steps;
    }

    /**
     * @param string $input
     * @param array $map
     * @param int $x
     * @param int $y
     * @return array
     */
    private function buildMap(string $input, array $map, int $x, int $y): array
    {
        $openPar = \strpos($input, '(');

        if (false === $openPar) {
            return $this->followPath($input, $map, $x, $y)[0];
        }

        [$map, $x, $y] = $this->followPath(\substr($input, 0, $openPar), $map, $x, $y);
        $input = \substr($input, $openPar + 1);

        $openPar = \strpos($input, '(');
        $closePar = \strpos($input, ')');
        if ($openPar !== false && $openPar < $closePar) {
            $closePar = $this->findRightCloseParenthesis($input);
            $subpaths = $this->splitParenthesis(\substr($input, 0, $closePar));
        } else {
            $subpaths = \explode('|', \substr($input, 0, $closePar));
        }

        $subpathX = $x;
        $subpathY = $y;
        foreach ($subpaths as $subpath) {
            $map = $this->buildMap($subpath, $map, $x, $y);
        }

        $input = \substr($input, $closePar + 1);
        $map =  $this->buildMap($input, $map, $subpathX, $subpathY);

        return $map;
    }

    /**
     * @param string $path
     * @param array $map
     * @param int $x
     * @param int $y
     * @return array
     */
    private function followPath(string $path, array $map, int $x, int $y): array
    {
        if (true === empty($path)) {
            return [$map, $x, $y];
        }

        foreach (\str_split($path) as $nextMove) {
            $map[$y][$x] = '.';
            switch ($nextMove) {
                case 'N':
                    $map = $this->goNorth($map, $x, $y);
                    $y -= 2;
                    break;
                case 'S':
                    $map = $this->goSouth($map, $x, $y);
                    $y += 2;
                    break;
                case 'E':
                    $map = $this->goEast($map, $x, $y);
                    $x += 2;
                    break;
                case 'W':
                    $map = $this->goWest($map, $x, $y);
                    $x -= 2;
                    break;
            }
        }
//        echo $this->printMap($map);

        return [$map, $x, $y];
    }

    /**
     * @param string $input
     * @return int
     */
    private function findRightCloseParenthesis(string $input): ?int
    {
        $parToClose = 1;
        foreach (\str_split($input) as $key => $value) {
            if ($value === '(') {
                $parToClose++;
            }
            if ($value === ')') {
                $parToClose--;
                if ($parToClose === 0) {
                    return $key;
                }
            }
        }

        return null;
    }

    /**
     * @param string $input
     * @return array
     */
    private function splitParenthesis(string $input): array
    {
        $subpaths = [];
        $openPar = 0;
        $subpath = '';

        foreach (\str_split($input) as $value) {
            switch ($value) {
                case '(':
                    $openPar++;
                    $subpath .= $value;
                    break;
                case ')':
                    $openPar--;
                    $subpath .= $value;
                    break;
                case '|':
                    if ($openPar === 0) {
                        $subpaths[] = $subpath;
                        $subpath = '';
                    } else {
                        $subpath .= $value;
                    }
                    break;
                default:
                    $subpath .= $value;
            }
        }
        $subpaths[] = $subpath;

        return $subpaths;
    }

    /**
     * @param array $map
     * @param int $x
     * @param int $y
     * @return array
     */
    private function goNorth(array $map, int $x, int $y): array
    {
        $map[$y - 1][$x] = '-';
        $map[$y - 1][$x - 1] = $map[$y - 1][$x - 1] ?? '#';
        $map[$y - 1][$x + 1] = $map[$y - 1][$x + 1] ?? '#';
        $map[$y][$x - 1] = $map[$y][$x - 1] ?? '#';
        $map[$y][$x + 1] = $map[$y][$x + 1] ?? '#';
        $map[$y + 1][$x - 1] = $map[$y + 1][$x - 1] ?? '#';
        $map[$y + 1][$x + 1] = $map[$y + 1][$x + 1] ?? '#';
        $map[$y + 1][$x] = $map[$y + 1][$x] ?? '#';

        return $map;
    }

    /**
     * @param array $map
     * @param int $x
     * @param int $y
     * @return array
     */
    private function goSouth(array $map, int $x, int $y): array
    {
        $map[$y + 1][$x] = '-';
        $map[$y - 1][$x - 1] = $map[$y - 1][$x - 1] ?? '#';
        $map[$y - 1][$x + 1] = $map[$y - 1][$x + 1] ?? '#';
        $map[$y - 1][$x] = $map[$y - 1][$x] ?? '#';
        $map[$y][$x - 1] = $map[$y][$x - 1] ?? '#';
        $map[$y][$x + 1] = $map[$y][$x + 1] ?? '#';
        $map[$y + 1][$x - 1] = $map[$y + 1][$x - 1] ?? '#';
        $map[$y + 1][$x + 1] = $map[$y + 1][$x + 1] ?? '#';

        return $map;
    }

    /**
     * @param array $map
     * @param int $x
     * @param int $y
     * @return array
     */
    private function goEast(array $map, int $x, int $y): array
    {
        $map[$y][$x + 1] = '|';
        $map[$y - 1][$x + 1] = $map[$y - 1][$x + 1] ?? '#';
        $map[$y + 1][$x + 1] = $map[$y + 1][$x + 1] ?? '#';
        $map[$y - 1][$x - 1] = $map[$y - 1][$x - 1] ?? '#';
        $map[$y + 1][$x - 1] = $map[$y + 1][$x - 1] ?? '#';
        $map[$y][$x - 1] = $map[$y][$x - 1] ?? '#';
        $map[$y - 1][$x] = $map[$y - 1][$x] ?? '#';
        $map[$y + 1][$x] = $map[$y + 1][$x] ?? '#';

        return $map;
    }

    /**
     * @param array $map
     * @param int $x
     * @param int $y
     * @return array
     */
    private function goWest(array $map, int $x, int $y): array
    {
        $map[$y][$x - 1] = '|';
        $map[$y][$x + 1] = $map[$y][$x + 1] ?? '#';

        $map[$y - 1][$x - 1] = $map[$y - 1][$x - 1] ?? '#';
        $map[$y - 1][$x] = $map[$y - 1][$x] ?? '#';
        $map[$y - 1][$x + 1] = $map[$y - 1][$x + 1] ?? '#';

        $map[$y + 1][$x - 1] = $map[$y + 1][$x - 1] ?? '#';
        $map[$y + 1][$x] = $map[$y + 1][$x] ?? '#';
        $map[$y + 1][$x + 1] = $map[$y + 1][$x + 1] ?? '#';

        return $map;
    }

    /**
     * @param array $map
     * @return string
     */
    public function printMap(array $map): string
    {
        $minY = \min(\array_keys($map));
        $maxY = \max(\array_keys($map));

        $minXs = [];
        $maxXs = [];
        foreach ($map as $row) {
            $minXs[] = \min(\array_keys($row));
            $maxXs[] = \max(\array_keys($row));
        }

        $minX = \min($minXs);
        $maxX = \min($maxXs);

        for ($y = $minY; $y <= $maxY; $y++) {
            for ($x = $minX; $x <= $maxX; $x++) {
                $map[$y][$x] = $map[$y][$x] ?? '?';
            }
        }

        $map[0][0] = 'X';

        $text = '';
        ksort($map);
        foreach ($map as $row) {
            ksort($row);
            $text .= implode('', $row) . "\n";
        }

        $text .= "\n\n";

        return $text;
    }

    /**
     * @param array $map
     * @param int $x
     * @param int $y
     * @param int|null $oldX
     * @param int|null $oldY
     * @return array
     */
    private function findPaths(array $map, int $x, int $y, int $oldX = null, int $oldY = null): array
    {
        $paths = [];
        if (false === ($x === $oldX && $y - 2 === $oldY)
            && true === isset($map[$y - 1][$x]) && $map[$y - 1][$x] === '-') {
            $paths[] = [$x, $y - 2];
        }
        if (false === ($x === $oldX && $y + 2 === $oldY)
            && true === isset($map[$y + 1][$x]) && $map[$y + 1][$x] === '-') {
            $paths[] = [$x, $y + 2];
        }
        if (false === ($x - 2 === $oldX && $y === $oldY)
            && true === isset($map[$y][$x - 1]) && $map[$y][$x - 1] === '|') {
            $paths[] = [$x - 2, $y];
        }
        if (false === ($x + 2 === $oldX && $y === $oldY)
            && true === isset($map[$y][$x + 1]) && $map[$y][$x + 1] === '|') {
            $paths[] = [$x + 2, $y];
        }

        return $paths;
    }
}
