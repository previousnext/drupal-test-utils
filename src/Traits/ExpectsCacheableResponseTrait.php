<?php

declare(strict_types=1);

namespace PNX\DrupalTestUtils\Traits;

use Behat\Mink\Exception\UnsupportedDriverActionException;
use Drupal\Core\Url;

/**
 * Helper functions to check if a response is cacheable in Dynamic Page Cache.
 *
 * Add a list of path patterns to your test base which are considered dynamic
 * or un-cacheable with $uncacheableDynamicPagePatterns.
 *
 * E.g
 * protected static array $uncacheableDynamicPagePatterns = [
 *   '/user/login',
 *   '/big_pipe/no-js',
 *   '/batch',
 *   '/node/add/.*',
 * ];
 *
 * Patterns will be merged up the class hierarchy. It is not necessary to
 * include patterns in your list that a parent class has already declared.
 */
trait ExpectsCacheableResponseTrait {

  /**
   * Detects un-cacheable responses and fails if that is not allowed.
   */
  protected function detectUncacheableResponse(string|Url $path, array $options = []): void {
    if ($this->isPathUncacheable($path)) {
      return;
    }

    try {
      if ($this->getSession()->getResponseHeader('X-Drupal-Dynamic-Cache') === 'UNCACHEABLE') {
        $this->fail(sprintf('Found an un-cacheable response at path: %s. If your test visits uncachable pages (cache-lifetime of zero) add them to static::$uncacheableDynamicPagePatterns in the test.', $this->buildUrl($path, $options)));
      }
    }
    catch (UnsupportedDriverActionException $e) {
      // Javascript tests don't support reading response headers.
    }
  }

  /**
   * Determines if the current path is un-cacheable.
   */
  private function isPathUncacheable(string|Url $path): bool {
    $uncacheableDynamicPagePatterns = $this->gatherDynamicPagePatterns();

    $currentPath = $path instanceof Url ? $path->toString() : $path;
    $currentUrl = $this->getSession()->getCurrentUrl();
    foreach ($uncacheableDynamicPagePatterns as $pattern) {
      $pattern = sprintf('#%s#', $pattern);
      if (preg_match($pattern, $currentUrl)) {
        return TRUE;
      }
      if (preg_match($pattern, $currentPath)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * Gathers patterns for uncachable paths.
   *
   * @return string[]
   *   An array of patterns.
   */
  private function gatherDynamicPagePatterns(): array {
    $class = static::class;
    $patterns = [];
    while ($class) {
      if (property_exists($class, 'uncacheableDynamicPagePatterns')) {
        $patterns = array_merge($patterns, $class::$uncacheableDynamicPagePatterns);
      }
      $class = get_parent_class($class);
    }

    return array_unique($patterns);
  }

}
