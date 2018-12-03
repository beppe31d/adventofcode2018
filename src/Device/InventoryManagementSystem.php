<?php

namespace AdventOfCode\Device;

/**
 * Class InventoryManagementSystem
 * @package AdventOfCode\Device
 */
class InventoryManagementSystem
{
    /** @var array */
    private $inputs;

    /**
     * ChronalCalibration constructor.
     * @param array $inputs
     */
    public function __construct(array $inputs)
    {
        $this->inputs = $inputs;
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
