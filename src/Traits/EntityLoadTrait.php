<?php

declare(strict_types=1);

namespace PNX\DrupalTestUtils\Traits;

use Drupal\Core\Entity\EntityInterface;

/**
 * Helper trait for loading entities.
 */
trait EntityLoadTrait {

  /**
   * Load an entity by some properties.
   *
   * @return ($only_one is true ? \Drupal\Core\Entity\EntityInterface|null : \Drupal\Core\Entity\EntityInterface[])
   *   A loaded entity or NULL, or an array of entities.
   */
  protected function loadEntityByProperty(string $entity_type_id, array $properties, bool $only_one = TRUE): EntityInterface|array|null {
    $storage = $this->container->get('entity_type.manager')->getStorage($entity_type_id);
    $this->container->get('entity_type.manager')->getStorage($entity_type_id)->resetCache();
    $entities = $storage->loadByProperties($properties);
    return $only_one ? (\reset($entities) ?: NULL) : $entities;
  }

  /**
   * Load the last created entity of a given type.
   */
  protected function lastCreatedEntity(string $type): EntityInterface {
    $type_manager = \Drupal::entityTypeManager();
    $id_key = $type_manager->getDefinition($type)->getKey('id');
    $results = \Drupal::entityQuery($type)->sort($id_key, 'DESC')->range(0, 1)->accessCheck(FALSE)->execute();
    $id = \array_shift($results);
    return $type_manager->getStorage($type)->load($id);
  }

}
