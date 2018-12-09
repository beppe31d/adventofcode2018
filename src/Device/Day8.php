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
 *
 * The second check is slightly more complicated: you need to find the value of the root node (A in the example above).
 *
 * The value of a node depends on whether it has child nodes.
 *
 * If a node has no child nodes, its value is the sum of its metadata entries. So, the value of node B is 10+11+12=33,
 * and the value of node D is 99.
 *
 * However, if a node does have child nodes, the metadata entries become indexes which refer to those child nodes. A
 * metadata entry of 1 refers to the first child node, 2 to the second, 3 to the third, and so on. The value of this
 * node is the sum of the values of the child nodes referenced by the metadata entries. If a referenced child node
 * does not exist, that reference is skipped. A child node can be referenced multiple time and counts each time it is
 * referenced. A metadata entry of 0 does not refer to any child node.
 *
 * For example, again using the above nodes:
 *
 * Node C has one metadata entry, 2. Because node C has only one child node, 2 references a child node which does not exist, and so the value of node C is 0.
 * Node A has three metadata entries: 1, 1, and 2. The 1 references node A's first child node, B, and the 2 references node A's second child node, C. Because node B has a value of 33 and node C has a value of 0, the value of node A is 33+33+0=66.
 * So, in this example, the value of the root node is 66.
 *
 * What is the value of the root node?
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
        echo "\n\n";
        echo 'What is the value of the root node? '. $this->nodeValue($tree);
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

    /**
     * @param array $tree
     * @return int
     */
    private function nodeValue(array $tree): int
    {
        if (true === empty($tree['children'])) {
            return \array_sum($tree['metadata']);
        }

        $nodeValue = 0;
        foreach ($tree['metadata'] as $key) {
            $key--;
            if (true === isset($tree['children'][$key])) {
                $nodeValue += $this->nodeValue($tree['children'][$key]);
            }
        }

        return $nodeValue;
    }
}
