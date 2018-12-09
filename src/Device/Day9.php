<?php

namespace AdventOfCode\Device;

/**
 * You talk to the Elves while you wait for your navigation system to initialize. To pass the time, they introduce you
 * to their favorite marble game.
 *
 * The Elves play this game by taking turns arranging the marbles in a circle according to very particular rules. The
 * marbles are numbered starting with 0 and increasing by 1 until every marble has a number.
 *
 * First, the marble numbered 0 is placed in the circle. At this point, while it contains only a single marble, it is
 * still a circle: the marble is both clockwise from itself and counter-clockwise from itself. This marble is
 * designated the current marble.
 *
 * Then, each Elf takes a turn placing the lowest-numbered remaining marble into the circle between the marbles that
 * are 1 and 2 marbles clockwise of the current marble. (When the circle is large enough, this means that there is one
 * marble between the marble that was just placed and the current marble.) The marble that was just placed then
 * becomes the current marble.
 *
 * However, if the marble that is about to be placed has a number which is a multiple of 23, something entirely
 * different happens. First, the current player keeps the marble they would have placed, adding it to their score.
 * In addition, the marble 7 marbles counter-clockwise from the current marble is removed from the circle and also
 * added to the current player's score. The marble located immediately clockwise of the marble that was removed
 * becomes the new current marble.
 *
 * What is the winning Elf's score?
 */

/**
 * Class Day9
 * @package AdventOfCode\Device
 */
class Day9 extends AbstractDay
{
    public function exec(): void
    {
        echo 'What is the winning Elf\'s score? ' . $this->calculateScore(411, 72059);
    }

    private function calculateScore($numberOfPlayers, $lastMarblePoints): int
    {
        $circle = [
            0
        ];
        $currentMarble = 0;
        $points = [];
        for ($i = 0; $i < $numberOfPlayers; $i++) {
            $points[$i] = 0;
        }

        for ($marble = 1; $marble <= $lastMarblePoints; $marble++) {
            $player = $marble % $numberOfPlayers;
            $key = \array_search($currentMarble, $circle, true);

            if ($marble % 23 === 0) {
                $pointsKey = $this->getPointsKey($circle, $key);

                $points[$player] += $marble;
                $points[$player] += $circle[$pointsKey];

                $currentMarble = $circle[$pointsKey + 1] ?? $circle[0];
                unset($circle[$pointsKey]);
                $circle = \array_values($circle);

                continue;
            }

            $circle = $this->addElementToCircle($circle, $key, $marble);
            $currentMarble = $marble;
        }

//        echo \implode(' ', $circle) . "\n\n";

        return \max($points);
    }

    private function addElementToCircle(array $circle, int $key, int $marble): array
    {
        if (isset($circle[$key + 1])) {
            \array_splice( $circle, $key + 2, 0, $marble);
        } else {
            \array_splice( $circle, 1, 0, $marble);
        }

        return $circle;
    }

    /**
     * @param array $circle
     * @param int $key
     * @return int
     */
    private function getPointsKey(array $circle, int $key): int
    {
        if ($key >= 7) {
            return $key - 7;
        }

        return \count($circle) - 7 + $key;
    }
}
