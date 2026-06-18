<?php

declare(strict_types=1);

namespace PNX\DrupalTestUtils\Traits;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Defines a trait for asserting violations.
 */
trait AssertViolationsTrait {

  /**
   * Asserts that the expected violations were found.
   *
   * @param array $expected
   *   Expected violation messages keyed by propery paths.
   * @param \Symfony\Component\Validator\ConstraintViolationListInterface $violations
   *   A list of violations.
   */
  protected static function assertViolations(array $expected, ConstraintViolationListInterface $violations): void {
    $list = [];
    foreach ($violations as $violation) {
      \assert($violation instanceof ConstraintViolation);
      $list[$violation->getPropertyPath()] = \strip_tags((string) $violation->getMessage());
    }
    self::assertEquals($expected, $list);
  }

  /**
   * Asserts that an expected violation exists.
   *
   * @param string $expected_violation
   *   Expected violation message.
   * @param \Symfony\Component\Validator\ConstraintViolationListInterface $violations
   *   A list of violations.
   */
  protected static function assertViolationExists(string $expected_violation, ConstraintViolationListInterface $violations): void {
    $list = [];
    foreach ($violations as $violation) {
      \assert($violation instanceof ConstraintViolation);
      $list[$violation->getPropertyPath()] = \strip_tags((string) $violation->getMessage());
    }
    self::assertContains($expected_violation, $list);
  }

}
