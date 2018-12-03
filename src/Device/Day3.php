<?php

namespace AdventOfCode\Device;

/**
 * The problem is that many of the claims overlap, causing two or more claims to cover part of the same areas. For example, consider the following claims:
 *
 * #1 @ 1,3: 4x4
 * #2 @ 3,1: 4x4
 * #3 @ 5,5: 2x2

 * The four square inches marked with X are claimed by both 1 and 2. (Claim 3, while adjacent to the others, does not overlap either of them.)
 *
 * If the Elves all proceed with their own plans, none of them will have enough fabric. How many square inches of
 * fabric are within two or more claims?
 */

/**
 * Amidst the chaos, you notice that exactly one claim doesn't overlap by even a single square inch of fabric with any
 * other claim. If you can somehow draw attention to it, maybe the Elves will be able to make Santa's suit after all!
 *
 * For example, in the claims above, only claim 3 is intact after all claims are made.
 *
 * What is the ID of the only claim that doesn't overlap?
 */

/**
 * Class Day3
 * @package AdventOfCode\Device
 */
class Day3 extends AbstractDay
{
    /** @var array */
    private $square;

    public function exec(): void
    {
        $this->populateSquare();
        echo 'How many square inches of fabric are within two or more claims? ' . $this->getOverlappedInches();
        echo "\n\n";
        echo 'What is the ID of the only claim that doesn\'t overlap? ' . $this->getUniqueClaimId();
    }

    /**
     * @return int
     */
    private function getOverlappedInches(): int
    {
        return $this->reduceSquare($this->square);
    }

    /**
     * @return int
     */
    private function getUniqueClaimId(): int
    {
        foreach ($this->inputs as $input) {
            $matches = [];
            \preg_match('/#(\d+) @ (\d+),(\d+): (\d+)x(\d+)/', $input, $matches);
            [, $id, $left, $top, $width, $height] = $matches;
            $validId = true;
            for ($row = 0; $row < $height; $row++) {
                for ($column = 0; $column < $width; $column++) {
                    if ($this->square[$top + $row][$left + $column] > 1)  {
                        $validId = false;
                    }
                }
            }

            if (true === $validId) {
                return $id;
            }
        }
        return 0;
    }

    /**
     * @param array $square
     * @return int
     */
    private function reduceSquare(array $square): int {
        return \array_reduce($square, function($carry, $item) {
            if (\is_array($item)) {
                return $carry + $this->reduceSquare($item);
            }

            if ($item > 1) {
                $carry++;
            }

            return $carry;
        }, 0);
    }

    private function populateSquare(): void
    {
        $square = [];
        foreach ($this->inputs as $input) {
            $matches = [];
            \preg_match('/#(\d+) @ (\d+),(\d+): (\d+)x(\d+)/', $input, $matches);
            [, , $left, $top, $width, $height] = $matches;

            for ($row = 0; $row < $height; $row++) {
                for ($column = 0; $column < $width; $column++) {
                    if (false === isset($square[$top + $row][$left + $column])) {
                        $square[$top + $row][$left + $column] = 1;
                    } else {
                        $square[$top + $row][$left + $column]++;
                    }
                }
            }
        }

        $this->square = $square;
    }
}
