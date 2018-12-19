<?php

namespace AdventOfCode\Device;

/**
 * @see https://adventofcode.com/2018/day/19
 */

use AdventOfCode\Helper\Registry;

/**
 * Class Day19
 * @package AdventOfCode\Device
 */
class Day19 extends AbstractDay
{
    /** @var Registry */
    private $registry;

    public function exec(): void
    {
        $this->registry = new Registry();
        $ip = (int) \str_replace(['#ip ', "\n"], '', $this->inputs[0]);
        unset($this->inputs[0]);
        echo 'What value is left in register 0 when the background process halts? ' . $this->program($ip);
    }

    /**
     * @param int $ip
     * @return int
     */
    private function program(int $ip): int
    {
        $result = [0, 0, 0, 0, 0];

        $operations = $this->getOperations();
        while (true) {
            if (false === isset($operations[$result[$ip]])) {
                return $result[$ip];
            }
            $operation = $operations[$result[$ip]];
            $instruction = $operation[0];
            $prev = $result;
            $result = $this->registry->$instruction($result, $operation);
            echo 'ip=' . $result[$ip] . ' [' . \implode(',', $prev) . '] ' . \implode(' ', $operation) . ' ['
                . \implode(',', $result) . ']' . "\n";

            $result[$ip]++;
        }

        return 0;
    }

    /**
     * @return array
     */
    private function getOperations(): array
    {
        return \array_values(\array_map(function (string $input) {
            return \explode(' ', \str_replace("\n", '', $input));
        }, $this->inputs));
    }
}
