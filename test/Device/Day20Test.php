<?php

namespace Test\AdventOfCode\Device;

use AdventOfCode\Device\Day20;
use PHPUnit\Framework\TestCase;

class Day20Test extends TestCase
{
    public function testBasicMap(): void
    {
        $day20 = new Day20(['^WNE$']);

        $steps = $day20->part1();

        $this->assertEquals(3, $steps);
    }

    public function testNestedPaths(): void
    {
        $day20 = new Day20(['^ENNWSWW(NEWS|)SSSEEN(WNSE|)EE(SWEN|)NNN$']);

        $steps = $day20->part1();

        $this->assertEquals(18, $steps);
    }

    public function testExample1(): void
    {
        $day20 = new Day20(['^ESSWWN(E|NNENN(EESS(WNSE|)SSS|WWWSSSSE(SW|NNNE)))$']);

        $steps = $day20->part1();

        $this->assertEquals(23, $steps);
    }

    public function testExample2(): void
    {
        $day20 = new Day20(['^WSSEESWWWNW(S|NENNEEEENN(ESSSSW(NWSW|SSEN)|WSWWN(E|WWS(E|SS))))$']);

        $steps = $day20->part1();

        $this->assertEquals(31, $steps);
    }
}
