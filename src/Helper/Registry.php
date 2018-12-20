<?php

namespace AdventOfCode\Helper;

class Registry
{
    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function addr(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = (int)$inputs[$operation[1]] + (int)$inputs[$operation[2]];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function addi(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = (int)$inputs[$operation[1]] + (int)$operation[2];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function mulr(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = (int)$inputs[$operation[1]] * (int)$inputs[$operation[2]];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function muli(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = (int)$inputs[$operation[1]] * (int)$operation[2];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function banr(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = (int)$inputs[$operation[1]] & (int)$inputs[$operation[2]];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function bani(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = (int)$inputs[$operation[1]] & (int)$operation[2];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function borr(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = (int)$inputs[$operation[1]] | (int)$inputs[$operation[2]];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function bori(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = (int)$inputs[$operation[1]] | (int)$operation[2];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function setr(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = (int)$inputs[$operation[1]];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function seti(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = (int)$operation[1];

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function gtir(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = (int)$operation[1] > (int)$inputs[$operation[2]] ? 1 : 0;

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function gtri(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = (int)$inputs[$operation[1]] > (int)$operation[2] ? 1 : 0;

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function gtrr(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = (int)$inputs[$operation[1]] > (int)$inputs[$operation[2]] ? 1 : 0;

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function eqir(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = (int)$operation[1] === (int)$inputs[$operation[2]] ? 1 : 0;

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function eqri(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = (int)$inputs[$operation[1]] === (int)$operation[2] ? 1 : 0;

        return $inputs;
    }

    /**
     * @param array $inputs
     * @param array $operation
     * @return array
     */
    public function eqrr(array $inputs, array $operation): array
    {
        $inputs[$operation[3]] = (int)$inputs[$operation[1]] === (int)$inputs[$operation[2]] ? 1 : 0;

        return $inputs;
    }
}
