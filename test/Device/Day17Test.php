<?php

namespace Test\AdventOfCode\Device;

use AdventOfCode\Device\Day17;
use AdventOfCode\Entity\WaterPoint;
use PHPUnit\Framework\TestCase;

class Day17Test extends TestCase
{
    public function testNoGround(): void
    {
        $map = [
            ['.', '|', '.'],
            ['.', '.', '.'],
        ];

        $point = new WaterPoint(1, 0);
        $day17 = new Day17([]);

        $this->assertFalse($day17->hasGround($point, $map));
    }

    public function testRightGround(): void
    {
        $map = [
            ['#', '|', '.'],
            ['#', '#', '#'],
        ];

        $point = new WaterPoint(1, 0);
        $day17 = new Day17([]);

        $this->assertTrue($day17->hasGround($point, $map));
    }

    public function testRightGround2(): void
    {
        $map = [
            ['#', '|', '|'],
            ['#', '#', '#'],
        ];

        $point = new WaterPoint(1, 0);
        $day17 = new Day17([]);

        $this->assertTrue($day17->hasGround($point, $map));
    }

    public function testRightGround3(): void
    {
        $map = [
            ['#', '|', '|'],
            ['~', '~', '~'],
        ];

        $point = new WaterPoint(1, 0);
        $day17 = new Day17([]);

        $this->assertTrue($day17->hasGround($point, $map));
    }

    public function testRightGround4(): void
    {
        $map = [
            ['.', '|', '~'],
            ['#', '#', '#'],
        ];

        $point = new WaterPoint(1, 0);
        $day17 = new Day17([]);

        $this->assertTrue($day17->hasGround($point, $map));
    }

    public function testFakeGround(): void
    {
        $map = [
            ['|', '|', '|'],
            ['|', '#', '~'],
        ];

        $point = new WaterPoint(1, 0);
        $day17 = new Day17([]);

        $this->assertFalse($day17->hasGround($point, $map));
    }

    public function testWaterGround(): void
    {
        $map = [
            ['~', '|', '~'],
            ['~', '~', '~'],
        ];

        $point = new WaterPoint(1, 0);
        $day17 = new Day17([]);

        $this->assertTrue($day17->hasGround($point, $map));
    }

    public function testWaterGround2(): void
    {
        $map = [
            ['~', '|', '#'],
            ['~', '~', '~'],
        ];

        $point = new WaterPoint(1, 0);
        $day17 = new Day17([]);

        $this->assertTrue($day17->hasGround($point, $map));
    }

    public function testWaterFlow(): void
    {
        $map = [
            ['|', '|', '|'],
            ['~', '~', '~'],
        ];

        $point = new WaterPoint(1, 0);
        $day17 = new Day17([]);

        $this->assertFalse($day17->hasGround($point, $map));
    }

    public function testWaterFlow2(): void
    {
        $map = [
        ['#', '|', '|'],
        ['#', '~', '~'],
    ];

        $point = new WaterPoint(1, 0);
        $day17 = new Day17([]);

        $this->assertTrue($day17->hasGround($point, $map));
    }

    public function testGetPoint(): void
    {
        $map = [
            ['.', '|', '|'],
            ['#', '#', '#'],
            ['.', '.', '.'],
        ];

        $point = new WaterPoint(1, 0);
        $day17 = new Day17([]);

        $this->assertNotNull($day17->getNextPoint($point, $map));
    }
}
