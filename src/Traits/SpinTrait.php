<?php

declare(strict_types=1);

namespace PNX\DrupalTestUtils\Traits;

/**
 * Defines a trait for executing a callable until it passes.
 */
trait SpinTrait {

  /**
   * Executes a callable until it returns TRUE.
   *
   * Executes executing a task until a condition is met.
   *
   * @param callable $lambda
   *   Callable to evaluate until TRUE or count is reached.
   * @param int $count
   *   (optional) Number of times to try, defaults to 10.
   * @param bool $throw
   *   (optional) Throw, TRUE to throw if the condition is not met.
   *
   * @return bool
   *   TRUE if lambda evaluated true.
   *
   * @throws \Exception
   *   When the condition is not met.
   */
  protected function spin(callable $lambda, $count = 10, $throw = TRUE): bool {
    $passes = 0;
    while ($passes < $count) {
      try {
        if ($lambda($this)) {
          return TRUE;
        }
      }
      catch (\Exception $e) {
        // Do nothing.
      }

      \usleep(500000);
      $passes++;
    }
    // Max reached.
    if ($throw) {
      throw new \Exception(\sprintf('Condition was not met after %d attempts', $count));
    }
    return FALSE;
  }

}
