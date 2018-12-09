<?php

namespace AdventOfCode\Device;

/**
 * The navigation system's license file consists of a list of numbers (your puzzle input). The numbers define a data
 * structure which, when processed, produces some kind of tree that can be used to calculate the license number.
 *
 * The tree is made up of nodes; a single, outermost node forms the tree's root, and it contains all other nodes in
 * the tree (or contains nodes that contain nodes, and so on).
 *
 * Specifically, a node consists of:
 *
 * A header, which is always exactly two numbers:
 * The quantity of child nodes.
 * The quantity of metadata entries.
 * Zero or more child nodes (as specified in the header).
 * One or more metadata entries (as specified in the header).
 * Each child node is itself a node that has its own header, child nodes, and metadata
 *
 * What is the sum of all metadata entries?
 */

/**
 * Class Day8
 * @package AdventOfCode\Device
 */
class Day8 extends AbstractDay
{
    public function exec(): void
    {
        $this->inputs = \explode(' ', $this->inputs[0]);
        $tree = $this->prepareTree();

        echo 'What is the sum of all metadata entries? ' . $this->checksum($tree);
    }

    /**
     * @return array
     */
    private function prepareTree(): array
    {
        $tree = [
            'children' => [],
            'metadata' => []
        ];

        $children = \array_shift($this->inputs);
        $metadata = \array_shift($this->inputs);

        for ($i = 0; $i < $children; $i++) {
            $tree['children'][] = $this->prepareTree();
        }

        for ($i = 0; $i < $metadata; $i++) {
            $tree['metadata'][] = \array_shift($this->inputs);
        }

        return $tree;
    }

    /**
     * @param array $tree
     * @param int $elements
     * @return int
     */
    private function checksum(array $tree): int
    {
        $checksum = \array_sum($tree['metadata']);
        foreach ($tree['children'] as $children) {
            $checksum += $this->checksum($children);
        }

        return $checksum;
    }
}
