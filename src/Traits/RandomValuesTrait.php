<?php

declare(strict_types=1);

namespace PNX\DrupalTestUtils\Traits;

use Drupal\Component\Utility\Random;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * Additional random value generation methods.
 */
trait RandomValuesTrait {

  /**
   * Generates a random URL.
   *
   * @return string
   *   Random URL.
   */
  protected function randomUrl(): string {
    $random = new Random();
    return \sprintf('https://%s.com/%s', $random->name(), $random->name());
  }

  /**
   * Generates a random email.
   *
   * @return string
   *   Random email.
   */
  protected function randomEmail(): string {
    $random = new Random();
    return \sprintf('%s@%s.com', $random->name(), $random->name());
  }

  /**
   * Generates a random lat lon pair.
   */
  protected function randomLatLonPair(): string {
    $lon = $this->randomPoint(-180, 180);
    $lat = $this->randomPoint(-84, 84);
    return \sprintf('POINT(%s %s)', $lon, $lat);
  }

  /**
   * Generates a random lat/lon point.
   */
  private function randomPoint(int $min, int $max): float {
    $number = \mt_rand($min, $max);
    $decimals = \mt_rand(1, \pow(10, 5)) / \pow(10, 5);
    return \round($number + $decimals, 5);
  }

  /**
   * Generates a random address.
   *
   * @return string
   *   Address.
   */
  public function randomAddress(): string {
    $random = new Random();
    return \sprintf('%d %s St, %s, %d', \rand(1, 200), $random->name(), $random->name(), \rand(2000, 2999));
  }

}
