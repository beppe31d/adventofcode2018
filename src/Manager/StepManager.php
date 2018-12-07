<?php

namespace AdventOfCode\Manager;

use AdventOfCode\Entity\StepState;

class StepManager
{
    /** @var array */
    private $readySteps;

    /** @var array */
    private $executableSteps;

    /** @var array */
    private $workers;

    /**
     * @param array $inputs
     * @return string
     */
    public function manageSteps(array $inputs): string
    {
        $steps = $this->prepareSteps($inputs);
        foreach ($steps as $step) {
            /** StepState $step */
            $this->executableSteps[$step->getId()] = $step;
            $this->readySteps[$step->getId()] = $step;
        }

        return $this->process();
    }

    /**
     * @param array $inputs
     * @return string
     */
    public function manageStepsWithDuration(array $inputs): string
    {
        $steps = $this->prepareSteps($inputs);
        foreach ($steps as $step) {
            /** StepState $step */
            $this->executableSteps[$step->getId()] = $step;
            $this->readySteps[$step->getId()] = $step;
        }

        $numberOfWorkers = 5;
        for ($i = 0 ; $i < $numberOfWorkers; $i++) {
            $this->workers[$i] = 0;
        }

        return $this->processWithDuration(0);
    }

    /**
     * @param int $time
     * @return int
     */
    private function processWithDuration(int $time): int
    {
        if (true === empty($this->executableSteps)) {
            return $time;
        }

        $executableSteps = $this->executableSteps;
        $this->executableSteps= [];
        foreach ($executableSteps as $executableStep) {
            /** @var StepState $executableStep */
            $workerId = $this->getFreeWorker($time);
            // Re-queue the step if there are no free workers or if the step is still blocked.
            if ($workerId === null ||
                ($executableStep->getStartingTime() !== null && $executableStep->getStartingTime() > $time)
            ) {
                $this->executableSteps[$executableStep->getId()] = $executableStep;
                continue;
            }
            $this->workers[$workerId] = $time + $this->charToNumber($executableStep->getId()) + 60;
            $readySteps = $executableStep->emitChange($this->workers[$workerId]);
            foreach ($readySteps as $step) {
                /** @var StepState $step */
                $this->executableSteps[$step->getId()] = $step;
                $this->readySteps[$step->getId()] = $step;
            }
        }

        $nextTime = \min(\array_filter($this->workers, function($item) use ($time) {
            return $item > $time;
        }));

        return $this->processWithDuration($nextTime);
    }

    private function getFreeWorker(int $time): ?int
    {
        foreach ($this->workers as $key => $worker) {
            if ($worker <= $time) {
                return $key;
            }
        }

        return null;
    }

    /**
     * @return string
     */
    private function process(): string
    {
        if (true === empty($this->readySteps)) {
            return '';
        }

        $readySteps = \array_filter($this->readySteps, function($step) {
           /** @var StepState $step */
           return $step->canTerminate();
        });

        /** @var StepState $nextStep */
        $nextStep = \min($readySteps);
        unset($this->readySteps[$nextStep->getId()]);

        $executableSteps = $this->executableSteps;
        $this->executableSteps= [];
        foreach ($executableSteps as $executableStep) {
            /** @var StepState $executableStep */
            $readySteps = $executableStep->emitChange();
            foreach ($readySteps as $step) {
                /** @var StepState $step */
                $this->executableSteps[$step->getId()] = $step;
                $this->readySteps[$step->getId()] = $step;
            }
        }

        $nextStep->setTerminated(true);
        return $nextStep->getId() . $this->process();
    }

    /**
     * @param array $inputs
     * @return array
     */
    private function prepareSteps(array $inputs): array
    {
        $steps = [];
        foreach ($inputs as [$step1, $step2]) {
            if (false === isset($steps[$step1])) {
                $steps[$step1] = new StepState($step1);
            }
            if (false === isset($steps[$step2])) {
                $steps[$step2] = new StepState($step2);
            }
            $steps[$step1]->addNextStep($steps[$step2]);
            $steps[$step2]->addDependsOn($steps[$step1]);
        }

        return \array_filter($steps, function($step) {
            /** @var StepState $step */
            $dependencies = $step->getDependsOn();
           return true === empty($dependencies);
        });
    }

    /**
     * @param StepState $step
     */
    public function addExecutabelStep(StepState $step): void
    {
        $this->executableSteps[] = $step;
    }

    /**
     * @param StepState $step
     */
    public function addReadyStep(StepState $step): void
    {
        $this->executableSteps[] = $step;
    }

    /**
     * @param string $dest
     * @return int
     */
    private function charToNumber(string $dest): int
    {
        return \ord(\strtolower($dest)) - 96;
    }
}
