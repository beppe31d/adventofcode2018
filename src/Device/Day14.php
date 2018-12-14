<?php

namespace AdventOfCode\Device;

/**
 * @see https://adventofcode.com/2018/day/14
 */

/**
 * Class Day14
 * @package AdventOfCode\Device
 */
class Day14 extends AbstractDay
{
    public function exec(): void
    {
        echo 'What are the scores of the ten recipes immediately after the number of recipes in your puzzle input? '
            . $this->getTenRecipes(919901);
    }

    /**
     * @param int $size
     * @return string
     */
    private function getTenRecipes(int $size): string
    {
        $recipes = [3, 7];
        $elves = [0, 1];
        while (true) {
            $newRecipe = $recipes[$elves[0]] + $recipes[$elves[1]];
            $recipes[] = (int)\substr($newRecipe, 0, 1);
            if (\strlen($newRecipe) === 2) {
                $recipes[] = (int)\substr($newRecipe, -1);
            }
            $elves[0] = $this->getNextPosition($elves[0], $recipes);
            $elves[1] = $this->getNextPosition($elves[1], $recipes);
            if (\count($recipes) >= $size + 10) {
                return \substr(implode('', $recipes), $size, 10);
            }
        }
    }

    /**
     * @param int $elfPosition
     * @param array $recipes
     * @return int
     */
    private function getNextPosition(int $elfPosition, array $recipes): int
    {
        $numberOfRecipes = \count($recipes);
        $moves = ($recipes[$elfPosition] + 1) % $numberOfRecipes;
        if ($moves < $numberOfRecipes - $elfPosition) {
            return $elfPosition + $moves;
        }

        return $moves - ($numberOfRecipes - $elfPosition);
    }
}
