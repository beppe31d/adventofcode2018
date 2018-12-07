<?php

namespace AdventOfCode\Device;

/**
 * The instructions specify a series of steps and requirements about which steps must be finished before others can
 * begin (your puzzle input). Each step is designated by a single letter. For example, suppose you have the following
 * instructions:
 *
 * Step C must be finished before step A can begin.
 * Step C must be finished before step F can begin.
 * Step A must be finished before step B can begin.
 * Step A must be finished before step D can begin.
 * Step B must be finished before step E can begin.
 * Step D must be finished before step E can begin.
 * Step F must be finished before step E can begin.
 *
 * Your first goal is to determine the order in which the steps should be completed. If more than one step is ready,
 * choose the step which is first alphabetically. In this example, the steps would be completed as follows:
 *
 * Only C is available, and so it is done first.
 * Next, both A and F are available. A is first alphabetically, so it is done next.
 * Then, even though F was available earlier, steps B and D are now also available, and B is the first alphabetically
 * of the three.
 * After that, only D and F are available. E is not available because only some of its prerequisites are complete.
 * Therefore, D is completed next.
 * F is the only choice, so it is done next.
 * Finally, E is completed.
 * So, in this example, the correct order is CABDFE.
 *
 * In what order should the steps in your instructions be completed?
 */

/**
 * As you're about to begin construction, four of the Elves offer to help. "The sun will set soon; it'll go faster if
 * we work together." Now, you need to account for multiple people working on steps simultaneously. If multiple steps
 * are available, workers should still begin them in alphabetical order.
 *
 * Each step takes 60 seconds plus an amount corresponding to its letter: A=1, B=2, C=3, and so on. So, step A takes
 * 60+1=61 seconds, while step Z takes 60+26=86 seconds. No time is required between steps.
 *
 * With 5 workers and the 60+ second step durations described above, how long will it take to complete all of the steps?
 */

use AdventOfCode\Manager\StepManager;

/**
 * Class Day7
 * @package AdventOfCode\Device
 */
class Day7 extends AbstractDay
{
    public function exec(): void
    {
        $inputs = $this->prepareInputs();
        $stepManager = new StepManager();

        echo 'In what order should the steps in your instructions be completed? ' . $stepManager->manageSteps($inputs);
        echo "\n\n";
        echo 'With 5 workers and the 60+ second step durations described above, how long will it take to complete all 
        of the steps? ' . $stepManager->manageStepsWithDuration($inputs);
    }

    private function prepareInputs(): array
    {
        return \array_map(function($input) {
            return \str_split(
                \str_replace(['Step ', ' must be finished before step ', ' can begin.', "\n"], '', $input)
            );
        }, $this->inputs);
    }
}
