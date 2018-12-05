<?php

namespace AdventOfCode\Device;

/**
 * The polymer is formed by smaller units which, when triggered, react with each other such that two adjacent units of
 * the same type and opposite polarity are destroyed. Units' types are represented by letters; units' polarity is
 * represented by capitalization. For instance, r and R are units with the same type but opposite polarity, whereas r
 * and s are entirely different types and do not react.
 *
 * For example:
 *
 * In aA, a and A react, leaving nothing behind.
 * In abBA, bB destroys itself, leaving aA. As above, this then destroys itself, leaving nothing.
 * In abAB, no two adjacent units are of the same type, and so nothing happens.
 * In aabAAB, even though aa and AA are of the same type, their polarities match, and so nothing happens.
 *
 * How many units remain after fully reacting the polymer you scanned?
 */

/**
 * One of the unit types is causing problems; it's preventing the polymer from collapsing as much as it should. Your
 * goal is to figure out which unit type is causing the most problems, remove all instances of it (regardless of
 * polarity), fully react the remaining polymer, and measure its length.
 *
 * For example, again using the polymer dabAcCaCBAcCcaDA from above:
 *
 * Removing all A/a units produces dbcCCBcCcD. Fully reacting this polymer produces dbCBcD, which has length 6.
 * Removing all B/b units produces daAcCaCAcCcaDA. Fully reacting this polymer produces daCAcaDA, which has length 8.
 * Removing all C/c units produces dabAaBAaDA. Fully reacting this polymer produces daDA, which has length 4.
 * Removing all D/d units produces abAcCaCBAcCcaA. Fully reacting this polymer produces abCBAc, which has length 6.
 * In this example, removing all C/c units was best, producing the answer 4.
 *
 * What is the length of the shortest polymer you can produce by removing all units of exactly one type and fully
 * reacting the result?
 */

/**
 * Class Day5
 * @package AdventOfCode\Device
 */
class Day5 extends AbstractDay
{
    public function exec(): void
    {
        echo 'How many units remain after fully reacting the polymer you scanned? '
            . $this->destroyAll($this->inputs[0]);
        echo "\n\n";
        echo 'What is the length of the shortest polymer you can produce by removing all units of exactly one type and 
        fully reacting the result? ' . $this->destroyBySinglePolymer();
    }

    /**
     * @return int
     */
    private function destroyAll(string $inputs): int
    {
        $replaces = [];
        $letters = range('a', 'z');
        foreach ($letters as $letter) {
            $replaces[] = $letter . \strtoupper($letter);
            $replaces[] = \strtoupper($letter) . $letter;
        }

        return $this->destroy($inputs, $replaces);
    }

    /**
     * @return int
     */
    public function destroyBySinglePolymer(): int
    {
        $lengthPerLetter = [];
        $letters = range('a', 'z');
        $inputs = $this->inputs[0];

        foreach ($letters as $letter) {
            $lengthPerLetter[$letter] = $this->destroyAll(\str_replace([$letter, \strtoupper($letter)], '', $inputs));
        }

        return min($lengthPerLetter);
    }

    /**
     * @param string $inputs
     * @param array $replaces
     * @return int
     */
    private function destroy(string $inputs, array $replaces): int
    {
        $searchAndDestroy = true;
        while (true === $searchAndDestroy) {
            $inputs = \str_replace($replaces, '', $inputs, $count);
            $searchAndDestroy = $count > 0;
        }

        return \strlen($inputs);
    }
}
