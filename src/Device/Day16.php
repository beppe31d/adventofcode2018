<?php

namespace AdventOfCode\Device;

/**
 * @see https://adventofcode.com/2018/day/16
 */

/**
 * Class Day16
 * @package AdventOfCode\Device
 */
class Day16 extends AbstractDay
{
    /** @var array */
    private CONST INSTRUCTIONS = [
        'addr',
        'addi',
        'mulr',
        'muli',
        'banr',
        'bani',
        'borr',
        'bori',
        'setr',
        'seti',
        'gtir',
        'gtri',
        'gtrr',
        'eqir',
        'eqri',
        'eqrr'
    ];

    /** @var @array */
    private $opcodes;

    public function exec(): void
    {
        echo 'how many samples in your puzzle input behave like three or more opcodes? ' . $this->threeOrMoreOpcodes();
        echo "\n\n";
        $opcodes = $this->findOpcodes();
        echo 'What value is contained in register 0 after executing the test program? '
            . $this->executeProgram($opcodes);
    }

    /**
     * @return int
     */
    private function threeOrMoreOpcodes(): int
    {
        $validOpcodes = 0;
        $before = null;
        $operation = null;
        $after = null;
        $total = 0;

        foreach ($this->inputs as $input) {
            $input = \str_replace("\n", '', $input);
            if ($before === null) {
                $matches = $this->searchBefore($input);
                if (null !== $matches) {
                    $before = $matches;
                }
            } elseif ($operation === null) {
                $operation = \explode(' ', $input);
            } else {
                $matches = $this->searchAfter($input);
                if (null !== $matches) {
                    $total++;
                    $after = $matches;
                    if (true === $this->hasEnoughOpcode($before, $operation, $after, 3)) {
                        $validOpcodes++;
                    }
                    $before = null;
                    $operation = null;
                    $after = null;
                }
            }
        }

        return $validOpcodes;
    }

    /**
     * @param array $opcodes
     * @return int
     */
    private function executeProgram(array $opcodes): int
    {
        $result = [0, 0, 0, 0];
        $start = false;
        $countEmptyRow = 0;
        foreach ($this->inputs as $input) {
            if ($start === false) {
                if ($input === "\n") {
                    $countEmptyRow++;
                    if ($countEmptyRow === 3) {
                        $start = true;
                    }
                } else {
                    $countEmptyRow = 0;
                }
                continue;
            }

            $operation = \explode(' ', \str_replace("\n", '', $input));
            $instruction = $opcodes[$operation[0]];
            $result = $this->$instruction($result, $operation);
        }

        return $result[0];
    }

    /**
     * @param array $before
     * @param array $operation
     * @param array $after
     * @param int $count
     * @return bool
     */
    private function hasEnoughOpcode(array $before, array $operation, array $after, int $count): bool
    {
        $output = \implode(',', $after);

        $validOpcodes = 0;
        foreach (self::INSTRUCTIONS as $instruction) {
            if ($output === \implode(',', $this->$instruction($before, $operation))) {
                if (false === isset($this->opcodes[$operation[0]]) ||
                    false === \in_array($instruction, $this->opcodes[$operation[0]], true)) {
                    $this->opcodes[$operation[0]][] = $instruction;
                }
                $validOpcodes++;
            }
        }

        return $validOpcodes >= $count;
    }

    /**
     * @return array
     */
    private function findOpcodes(): array
    {
        $opcodes = [];
        while (false === empty($this->opcodes)) {
            $oneElement = \array_filter($this->opcodes, function($a) {
               return \count($a) === 1;
            });
            foreach ($oneElement as $elKey => $searchElement) {
                $opcodes[$elKey] = \array_shift($searchElement);
                unset($this->opcodes[$elKey]);
                foreach ($this->opcodes as $opcodeKey => $opcode) {
                    $key = \array_search($opcodes[$elKey], $opcode, true);
                    if ($key !== false) {
                        unset($this->opcodes[$opcodeKey][$key]);
                    }
                }
            }
        }

        return $opcodes;
    }

    /**
     * @param string $input
     * @param string $regex
     * @return array|null
     */
    private function search(string $input, string $regex): ?array
    {
        $matches = null;
        preg_match($regex, $input, $matches);
        if (\count($matches) > 0) {
            unset($matches[0]);
            return \array_values($matches);
        }

        return null;
    }

    /**
     * @param string $input
     * @return array|null
     */
    private function searchBefore(string $input): ?array
    {
        return $this->search($input, '/Before: \[(\d+), (\d+), (\d+), (\d+)\]*/');
    }

    /**
     * @param string $input
     * @return array|null
     */
    private function searchAfter(string $input): ?array
    {
        return $this->search($input, '/After:  \[(\d+), (\d+), (\d+), (\d+)\]*/');
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function addr(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = $inputs[$operation[1]] + $inputs[$operation[2]];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function addi(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = $inputs[$operation[1]] + $operation[2];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function mulr(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = $inputs[$operation[1]] * $inputs[$operation[2]];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function muli(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = $inputs[$operation[1]] * $operation[2];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function banr(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = $inputs[$operation[1]] & $inputs[$operation[2]];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function bani(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = $inputs[$operation[1]] & $operation[2];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function borr(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = $inputs[$operation[1]] | $inputs[$operation[2]];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function bori(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = $inputs[$operation[1]] | $operation[2];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function setr(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = $inputs[$operation[1]];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function seti(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = $operation[1];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function gtir(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = $operation[1] > $inputs[$operation[2]] ? 1 : 0;

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function gtri(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = $inputs[$operation[1]] > $operation[2] ? 1 : 0;

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function gtrr(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = $inputs[$operation[1]] > $inputs[$operation[2]] ? 1 : 0;

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function eqir(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = $operation[1] === $inputs[$operation[2]] ? 1 : 0;

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function eqri(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = $inputs[$operation[1]] === $operation[2] ? 1 : 0;

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function eqrr(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = $inputs[$operation[1]] === $inputs[$operation[2]] ? 1 : 0;

        return $inputs;
    }
}
