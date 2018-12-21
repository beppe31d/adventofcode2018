<?php

namespace AdventOfCode\Device;

/**
 * Class AbstractDay
 * @package AdventOfCode\Device
 */
abstract class AbstractDay
{
    /** @var array */
    protected $inputs;

    /**
     * ChronalCalibration constructor.
     * @param array $inputs
     */
    public function __construct(array $inputs)
    {
        $this->inputs = $inputs;
    }

    abstract public function exec(): void;

    /**
     * @return array
     */
    protected function getMap(): array
    {
        $map = [];
        foreach($this->inputs as $input) {
            $map[] = \str_split(\str_replace("\n", '', $input));
        }

        return $map;
    }
}
