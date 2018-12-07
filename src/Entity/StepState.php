<?php

namespace AdventOfCode\Entity;

/**
 * Class StepState
 * @package AdventOfCode\Entity
 */
class StepState
{
    /** @var string */
    private $id;

    /** @var array */
    private $nextSteps;

    /** @var array */
    private $dependsOn;

    /** @var array */
    private $waitForTermination;

    /** @var bool */
    private $terminated;

    /** @var int */
    private $startingTime;

    /**
     * StepState constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
        $this->nextSteps = [];
        $this->dependsOn = [];
        $this->waitForTermination = [];
        $this->terminated = false;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param StepState $step
     */
    public function addNextStep(StepState $step): void
    {
        $this->nextSteps[$step->getId()] = $step;
    }

    /**
     * @return array
     */
    public function getNextSteps(): array
    {
        return $this->nextSteps;
    }

    /**
     * @return array
     */
    public function getDependsOn(): array
    {
        return $this->dependsOn;
    }

    /**
     * @param StepState $step
     */
    public function addDependsOn(StepState $step): void
    {
        $this->dependsOn[$step->getId()] = $step;
        $this->addWaitingForTermination($step);
    }

    /**
     * @param StepState $step
     */
    public function addWaitingForTermination(StepState $step): void
    {
        $this->waitForTermination[$step->getId()] = $step;
    }

    /**
     * @param int|null $time
     * @return array
     */
    public function emitChange(int $time = null): array
    {
        $readySteps = [];
        foreach ($this->nextSteps as $dependency) {
            /** @var StepState $dependency */
            $step = $dependency->receiveChange($this, $time);
            if (null !== $step) {
                $readySteps[] = $step;
            }
        }

        return $readySteps;
    }

    /**
     * @param StepState $step
     * @return StepState|null
     */
    public function receiveChange(StepState $step, int $time = null): ?StepState
    {
        unset($this->dependsOn[$step->getId()]);
        if (true === empty($this->dependsOn)) {
            $this->startingTime = $time;
            return $this;
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isTerminated(): bool
    {
        return $this->terminated;
    }

    /**
     * @param bool $terminated
     */
    public function setTerminated(bool $terminated): void
    {
        $this->terminated = $terminated;
    }

    /**
     * @return bool
     */
    public function canTerminate(): bool
    {
        foreach ($this->waitForTermination as $waiting) {
            /** @var StepState $waiting */
            if (false === $waiting->isTerminated()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return int
     */
    public function getStartingTime(): ?int
    {
        return $this->startingTime;
    }
}
