<?php

namespace Drupal\image\Plugin\DataType;

use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\TypedData\MapDataDefinition;

/**
 * @todo perhaps this should not exist, and we should have \Drupal\image\Plugin\DataType\ComputedImageStyle::getDataDefinition() instead? I can't figure it out.
 */
class ImageStyleDataDefinition extends MapDataDefinition {

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions() {
    return [
      'url' => DataDefinition::create('uri'),
      'width' => DataDefinition::create('integer'),
      'height' => DataDefinition::create('integer'),
    ];
  }

}
