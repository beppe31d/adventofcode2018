<?php
include_once 'vendor/autoload.php';

$start = microtime(true);
$inputs = file('./input/day' . $argv[1] . '.input');
$class = 'AdventOfCode\Device\Day' . $argv[1];
(new $class($inputs))->exec();
$time = microtime(true) - $start;
echo "\n\n" . 'Total time: ' . $time;
