<?php

namespace AdventOfCode\Device;

class ChronalCalibration
{
    /** @var array */
    private $inputs;

    /** @var array */
    private $frequenciesReached;

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
    public function getLastFrequency(): int
    {
        return \array_sum($this->inputs);
    }

    /**
     * @return int|null
     */
    public function getFirstFrequencyReachedTwice(): ?int
    {
        $currentFrequency = 0;
        $this->frequenciesReached = [
            $currentFrequency
        ];
        $firstFrequencyReachedTwice = null;
        while ($firstFrequencyReachedTwice === null) {
            foreach($this->inputs as $input) {
                $currentFrequency += (int)$input;
                if ($firstFrequencyReachedTwice === null
                    && true === \in_array($currentFrequency, $this->frequenciesReached, true)) {
                    return $currentFrequency;
                }
                $this->frequenciesReached[] = $currentFrequency;
            }
        }

        return null;
    }
}
