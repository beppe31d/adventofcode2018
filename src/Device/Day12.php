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
    }

    /**
     * @param string $state
     * @param int $generations
     * @return int
     */
    private function sumPlants(string $state, int $generations): int
    {
        $plants = $this->getPlantsInGeneration($state);
        echo $this->getPlantsInGeneration($state) . ' --- ' . $state . "\n";

        for ($g = 0; $g < $generations; $g++) {
            $state = $this->getNextGeneration($state);
            $plants += $this->getPlantsInGeneration($state);
            echo $this->getPlantsInGeneration($state) . ' --- ' . $state . "\n";
        }

        return $plants;
    }

    /**
     * @param string $state
     * @return int
     */
    private function getPlantsInGeneration(string $state): int
    {
        return \substr_count($state, '#');
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

        $empty = '.....';
        if (0 === \strpos($nextState, $empty)) {
            $nextState = \substr($nextState, 4);
        }

        if (\substr($nextState, -5) === $empty) {
            $nextState = \substr($nextState, 0, -4);
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
