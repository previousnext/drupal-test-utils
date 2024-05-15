<?php

declare(strict_types=1);

namespace PNX\DrupalTestUtils\Traits;

use Drupal\Core\Config\Config;

trait ConfigTrait {

  /**
   * Original configuration values for restoration after the test run.
   */
  protected array $originalConfiguration = [];    

  /**
   * Set config values noting future values if required.
   *
   * @param array $config_values
   *   Array of array values keyed by config object key.
   * @param bool $mark
   *   TRUE to record value for resetting later.
   */
  protected function setConfigValues(array $config_values, bool $mark = TRUE): void {
    $config = $this->container->get('config.factory');
    foreach ($config_values as $key => $values) {
      /** @var \Drupal\Core\Config\ImmutableConfig $entry */
      $entry = $config->getEditable($key);
      if ($mark && !isset($this->originalConfiguration[$key])) {
        $this->originalConfiguration[$key] = [];
      }
      foreach ($values as $value_key => $value) {
        if ($mark) {
          $this->originalConfiguration[$key][$value_key] = $entry->get($value_key);
        }
        $entry->set($value_key, $value);
      }
      $entry->save();
    }
  }

  /**
   * Configuration accessor for tests. Returns non-overridden configuration.
   *
   * @param string $name
   *   Configuration name.
   *
   * @return \Drupal\Core\Config\Config
   *   The configuration object with original configuration data.
   */
  protected function config(string $name): Config {
    return $this->container->get('config.factory')->getEditable($name);
  }

}