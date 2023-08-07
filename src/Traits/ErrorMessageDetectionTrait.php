<?php

declare(strict_types=1);

namespace PNX\DrupalTestUtils\Traits;

use Drupal\Component\Utility\Html;

/**
 * Helper functions to detect if the response output contains an error.
 */
trait ErrorMessageDetectionTrait {

  /**
   * Detects an error in the response output and fails with the error message.
   */
  protected function detectErrorMessageInResponseOutput(string $response): void {
    $error = 'The website encountered an unexpected error. Please try again later.';
    if (str_starts_with($response, $error)) {
      $plain = Html::decodeEntities(strip_tags(str_ireplace('<br>', "\n", $response)));
      throw new \ErrorException($plain);
    }
  }

}
