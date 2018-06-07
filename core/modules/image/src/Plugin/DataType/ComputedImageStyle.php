<?php

namespace Drupal\image\Plugin\DataType;

use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\TypedData\MapDataDefinition;
use Drupal\Core\TypedData\Plugin\DataType\Map;

/**
 * The image style data type.
 *
 * @ingroup typed_data
 *
 * @DataType(
 *   id = "image_style",
 *   label = @Translation("Image style metadata"),
 *   definition_class = "\Drupal\image\Plugin\DataType\ImageStyleDataDefinition",
 *   list_class = "\Drupal\image\Plugin\DataType\ComputedImageStyleList",
 * )
 *
 * @todo implement CacheableDependencyInterface
 *
 * @internal
 */
class ComputedImageStyle extends Map {

  protected $url = NULL;
  protected $width = NULL;
  protected $height = NULL;

  /**
   * {@inheritdoc}
   *
   * @todo receive a value object that implements CacheableDependencyInterface
   */
  public function setValue($value, $notify = TRUE) {
    $this->url = $value['url'];
    $this->width = $value['width'];
    $this->height = $value['height'];
    // Notify the parent of any changes.
    if ($notify && isset($this->parent)) {
      $this->parent->onChange($this->name);
    }
    parent::setValue($value);
  }

  /**
   * {@inheritdoc}
   */
  public function getValue() {
    return [
      'url' => $this->url,
      'width' => $this->width,
      'height' => $this->height,
    ];
  }

}
