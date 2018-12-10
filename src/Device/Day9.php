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
 * Amused by the speed of your answer, the Elves are curious:
 *
 * What would the new winning Elf's score be if the number of the last marble were 100 times larger?
 */

use AdventOfCode\Entity\Marble;

/**
 * Class Day9
 * @package AdventOfCode\Device
 */
class Day9 extends AbstractDay
{
    /** @var array */
    private $marbles;

    public function exec(): void
    {

        echo 'What is the winning Elf\'s score? ' . $this->calculateScore(411, 72059);
        echo "\n\n";
        echo 'What would the new winning Elf\'s score be if the number of the last marble were 100 times larger? '
            . $this->calculateScore(411, 7205900);
    }

    /**
     * @param $numberOfPlayers
     * @param $lastMarblePoints
     * @return int
     */
    private function calculateScore(int $numberOfPlayers, int $lastMarblePoints): int
    {
        $currentMarble = new Marble();
        $currentMarble->setValue(0);
        $currentMarble->setNext(0);
        $currentMarble->setPrev(0);

        // To avoid memory limit the indexed list stores the index of marbles array.
        $this->marbles = [$currentMarble];

        $points = [];
        for ($i = 0; $i < $numberOfPlayers; $i++) {
            $points[$i] = 0;
        }

        for ($i = 1; $i <= $lastMarblePoints; $i++) {
            $player = $i % $numberOfPlayers;

            if ($i % 23 === 0) {
                $points[$player] += $i;

                for ($c = 0; $c < 7; $c++) {
                    $currentMarble = $this->marbles[$currentMarble->getPrev()];
                }

                $points[$player] += $currentMarble->getValue();
                $currentMarble = $this->resetMarble($currentMarble);
                continue;
            }

            $currentMarble = $this->addMarble($currentMarble, $i);
        }

        return \max($points);
    }

    /**
     * @param Marble $currentMarble
     * @param int $value
     * @return Marble
     */
    private function addMarble(Marble $currentMarble, int $value): Marble
    {
        $marble = new Marble();
        $marble->setValue($value);
        $key = \count($this->marbles);
        $this->marbles[$key] = $marble;

        $next = $this->marbles[$currentMarble->getNext()]->getNext();
        $this->marbles[$next]->setPrev($key);
        $marble->setPrev($currentMarble->getNext());
        $marble->setNext($next);
        $this->marbles[$currentMarble->getNext()]->setNext($key);

        return $marble;
    }

    /**
     * @param Marble $currentMarble
     * @return Marble
     */
    private function resetMarble(Marble $currentMarble): Marble
    {
        $key = \array_search($currentMarble, $this->marbles, true)[0];
        $next = $currentMarble->getNext();
        $prev = $currentMarble->getPrev();
        $this->marbles[$next]->setPrev($prev);
        $this->marbles[$prev]->setNext($next);
        unset($this->marbles[$key]);

        return $this->marbles[$next];
    }
}
