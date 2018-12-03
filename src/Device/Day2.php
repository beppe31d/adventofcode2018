<?php

namespace AdventOfCode\Device;

/**
 * To make sure you didn't miss any, you scan the likely candidate boxes again, counting the number that have an ID containing exactly two of any letter and then separately counting those with exactly three of any letter. You can multiply those two counts together to get a rudimentary checksum and compare it to what your device predicts.
 *
 * For example, if you see the following box IDs:
 *
 * abcdef contains no letters that appear exactly two or three times.
 * bababc contains two a and three b, so it counts for both.
 * abbcde contains two b, but no letter appears exactly three times.
 * abcccd contains three c, but no letter appears exactly two times.
 * aabcdd contains two a and two d, but it only counts once.
 * abcdee contains two e.
 * ababab contains three a and three b, but it only counts once.
 * Of these box IDs, four of them contain a letter which appears exactly twice, and three of them contain a letter
 * which appears exactly three times. Multiplying these together produces a checksum of 4 * 3 = 12.
 *
 * What is the checksum for your list of box IDs?
 */

/**
 * Confident that your list of box IDs is complete, you're ready to find the boxes full of prototype fabric.
 *
 * The boxes will have IDs which differ by exactly one character at the same position in both strings. For example,
 * given the following box IDs:
 *
 * abcde
 * fghij
 * klmno
 * pqrst
 * fguij
 * axcye
 * wvxyz
 * The IDs abcde and axcye are close, but they differ by two characters (the second and fourth). However, the IDs fghij
 * and fguij differ by exactly one character, the third (h and u). Those must be the correct boxes.
 *
 * What letters are common between the two correct box IDs? (In the example above, this is found by removing the
 * differing character from either ID, producing fgij.)
 */

/**
 * Class Day2
 * @package AdventOfCode\Device
 */
class Day2 extends AbstractDay
{
    public function exec(): void
    {
        echo 'What is the checksum for your list of box IDs? ' . $this->getChecksum();

        echo "\n\n";

        echo 'What letters are common between the two correct box IDs? ' . $this->getCommonLetters();
    }

    /**
     * @return int
     */
    public function getChecksum(): int
    {
        $twoTimes = 0;
        $threeTimes = 0;
        foreach ($this->inputs as $input) {
            $result = $this->findMultipleLetters($input);
            $twoTimes += $result[0];
            $threeTimes += $result[1];
        }

        return $twoTimes * $threeTimes;
    }

    /**
     * @param string $word
     * @return array
     */
    private function findMultipleLetters(string $word): array
    {
        $maxCount = 3;
        $wordArray = \str_split($word);

        $charsCount = [];

        foreach($wordArray as $char) {
            if (false === isset($charsCount[$char])) {
                $charsCount[$char] = 1;
            } else {
                $charsCount[$char]++;
                if ($charsCount[$char] > $maxCount) {
                    $charsCount[$char] = $maxCount;
                }
            }
        }

        return [\in_array(2, $charsCount, true), \in_array(3, $charsCount, true)];
    }

    /**
     * @return null|string
     */
    public function getCommonLetters(): ?string
    {
        foreach ($this->inputs as $input) {
            $result = $this->findLetters($input);
            if (null !== $result) {
                return $result;
            }
        }

        return null;
    }

    /**
     * @param string $search
     * @return null|string
     */
    private function findLetters(string $search): ?string {
        $searchArray = \str_split($search);
        foreach ($this->inputs as $input) {
            $intersect = [];
            $inputArray = \str_split($input);
            foreach ($inputArray as $key => $char) {
                if ($inputArray[$key] === $searchArray[$key]) {
                    $intersect[] = $char;
                }
            }
            if (\count($intersect) === \count($searchArray) - 1) {
                return \implode('', $intersect);
            }
        }
        return null;
    }
}
