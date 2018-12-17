<?php

namespace AdventOfCode\Device;

/**
 * @see https://adventofcode.com/2018/day/12
 */

/**
 * Class Day12
 * @package AdventOfCode\Device
 */
class Day12 extends AbstractDay
{
    /** @var $patterns */
    private $patterns;

    public function exec(): void
    {
        $initialState = \str_replace(['initial state: ', "\n"], '', $this->inputs[0]);
        $this->hydratePatters();
        echo 'After 20 generations, what is the sum of the numbers of all pots which contain a plant? '
            . $this->sumPlants($initialState, 20);
        echo "\n\n";

        /**
         * Pattern for part 2 (generation, start index, sum)
         * 1994 - 1902 - 171919
         * 1995 - 1904 - 172005
         * 1996 - 1906 - 172091
         * 1997 - 1904 - 172177
         * 1998 - 1906 - 172263
         *
         * Difference is 86 after each step.
         */
        echo 'After fifty billion (50000000000) generations, what is the sum of the numbers of all pots which contain a
        plant? ' . ((50000000000 - 1000) * 86 + $this->sumPlants($initialState, 1000));
    }

    /**
     * @param string $state
     * @param int $generations
     * @return int
     */
    private function sumPlants(string $state, int $generations): int
    {
        $index = 0;
        for ($g = 0; $g < $generations; $g++) {
            $state = $this->getNextGeneration($state);

            $index -= 2;
            $empty = '.....';
            if (0 === \strpos($state, $empty)) {
                $state = \substr($state, 4);
                $index += 4;
            }

            if (\substr($state, -5) === $empty) {
                $state = \substr($state, 0, -4);
            }
        }
        echo $state . "\n";

        return $this->getPlantsInGeneration($state, $index);
    }

    /**
     * @param string $state
     * @param int $index
     * @return int
     */
    private function getPlantsInGeneration(string $state, int $index): int
    {
        $result = 0;
        foreach (\str_split($state) as $pot) {
            if ($pot === '#') {
                $result += $index;
            }
            $index++;
        }

        return $result;
    }

    /**
     * @param string $state
     * @return string
     */
    private function getNextGeneration(string $state): string
    {
        $nextState = '..';
        $length = \strlen($state);
        $state = '..' . $state . '..';
        for ($i = 0; $i <= $length; $i++) {
            $nextState .= $this->hasPattern(\substr($state, $i, 5)) ? '#' : '.';
        }

        return $nextState;
    }

    /**
     * @param string $pattern
     * @return bool
     */
    private function hasPattern(string $pattern): bool
    {
        return \in_array($pattern, $this->patterns, true);
    }

    private function hydratePatters(): void
    {
        unset($this->inputs[0], $this->inputs[1]);

        foreach ($this->inputs as $pattern) {
            [$input, $output] = \explode(' => ', \str_replace("\n", '', $pattern));
            if ($output === '#') {
                $this->patterns[] = $input;
            }
        }
    }
}
