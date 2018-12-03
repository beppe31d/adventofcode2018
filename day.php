<?php
include_once 'vendor/autoload.php';

$inputs = file('./input/day' . $argv[1] . '.input');
$class = 'AdventOfCode\Device\Day' . $argv[1];
(new $class($inputs))->exec();