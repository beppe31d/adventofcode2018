<?php

namespace AdventOfCode\Device;

/**
 * Strategy 1: Find the guard that has the most minutes asleep. What minute does that guard spend asleep the most?
 *
 * In the example above, Guard #10 spent the most minutes asleep, a total of 50 minutes (20+25+5), while Guard #99
 * only slept for a total of 30 minutes (10+10+10). Guard #10 was asleep most during minute 24 (on two days, whereas
 * any other minute the guard was asleep was only seen on one day).
 *
 * While this example listed the entries in chronological order, your entries are in the order you found them. You'll
 * need to organize them before they can be analyzed.
 *
 * What is the ID of the guard you chose multiplied by the minute you chose? (In the above example, the answer would
 * be 10 * 24 = 240.)
 */

/**
 * Strategy 2: Of all guards, which guard is most frequently asleep on the same minute?
 * What is the ID of the guard you chose multiplied by the minute you chose?
 */

/**
 * Class Day4
 * @package AdventOfCode\Device
 */
class Day4 extends AbstractDay
{
    public function exec(): void
    {
        $inputs = $this->countMinutesAsleepPerGuard($this->hydrateInputs());

        // There could be multiple results, like with my input strategy 2 has 2 possibilities.
        echo 'Strategy 1 ' . $this->findGuardWithMostMinutesAsleep($inputs);
        echo "\n\n";
        echo 'Strategy 2 ' . $this->findGuardWithMaxSameMinuteAsleep($inputs);
    }

    /**
     * @param array $inputs
     * @return int
     */
    private function findGuardWithMostMinutesAsleep(array $inputs): int
    {
        $maxMinutePerGuard = $this->getMaxMinuteAsleepPerGuard($inputs);
        \usort(
            $maxMinutePerGuard,
            [$this, 'sortGuardStrategy1']
        );

        $guard = $maxMinutePerGuard[0];
        return $guard['guardId'] * $guard['minute'];
    }

    /**
     * @param array $inputs
     * @return int
     */
    private function findGuardWithMaxSameMinuteAsleep(array $inputs): int
    {
        $maxMinutePerGuard = $this->getMaxMinuteAsleepPerGuard($inputs);
        \usort(
            $maxMinutePerGuard,
            [$this, 'sortGuardStrategy2']
        );

        $guard = $maxMinutePerGuard[0];
        return $guard['guardId'] * $guard['minute'];
    }

    /**
     * @param array $guards
     * @return array
     */
    private function getMaxMinuteAsleepPerGuard(array $guards) {
        $maxMinutePerGuard = [];

        foreach ($guards as $guardId => $guardMinutes) {
            $maxMinutePerGuard[$guardId] = [
                'guardId' => $guardId,
                'minute' => \array_keys($guardMinutes, \max($guardMinutes))[0],
                'count' => max($guardMinutes),
                'sum' => \array_sum($guardMinutes)
            ];
        }

        return $maxMinutePerGuard;
    }

    /**
     * @param array $inputs
     * @return array
     */
    private function countMinutesAsleepPerGuard(array $inputs): array
    {
        $guards = [];
        $currentGuard = null;
        $startSleep = null;
        $asleep = false;

        foreach ($inputs as [$time, $message]) {
            \preg_match('/Guard #([\d]+) begins shift/', $message, $matches);
            if (false === empty($matches)) {
                $guardId = $matches[1];
                if (false === isset($guards[$guardId])) {
                    $guards[$guardId] = $this->initializeGuard();
                }
                if ($asleep === false && $currentGuard !== null) {
                    $guards[$currentGuard] = $this->trackSleep($guards[$currentGuard], $startSleep, 60);
                }

                $currentGuard = $guardId;
            }  elseif ($message === 'falls asleep') {
                $startSleep = \substr($time, -2);
                $asleep = true;
            } else {
                $guards[$currentGuard] =
                    $this->trackSleep($guards[$currentGuard], $startSleep, \substr($time, -2));
                $asleep = false;
            }
        }

        return $guards;
    }

    /**
     * @param array $guardMinutes
     * @param int $startSleep
     * @param int $endSleep
     * @return array
     */
    private function trackSleep(array $guardMinutes, int $startSleep, int $endSleep): array
    {
        for ($i = $startSleep; $i < $endSleep; $i++) {
            $guardMinutes[$i]++;
        }

        return $guardMinutes;
    }

    /**
     * @return array
     */
    private function initializeGuard(): array
    {
        $return = [];
        for ($i = 0; $i < 60; $i++) {
            $return[$i] = 0;
        }

        return $return;
    }

    /**
     * @return array
     */
    private function hydrateInputs(): array
    {
        $inputs = \array_map([$this, 'splitItem'], $this->inputs);
        \usort(
            $inputs,
            [$this, 'sortItems']
        );
        return $inputs;
    }

    /**
     * @param $item
     * @return array
     */
    private function splitItem($item): array
    {
        return [
            substr($item, 1, 16),
            str_replace("\n", '', substr($item, 19))
        ];
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    private function sortItems($a, $b): bool
    {
        return $a[0] > $b[0];
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    private function sortGuardStrategy1($a, $b): bool
    {
        return $a['sum'] < $b['sum'];
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    private function sortGuardStrategy2($a, $b): bool
    {
        return $a['count'] < $b['count'];
    }
}

