<?php

namespace Drupal\image\Plugin\DataType;

use Drupal\Core\TypedData\Plugin\DataType\ItemList;
use Drupal\file\FileInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\image\ImageStyleInterface;

/**
 * List class for \Drupal\image\Plugin\DataType\ImageStyle.
 *
 * @ingroup typed_data
 */
class ComputedImageStyleList extends ItemList {

  /**
   * Compute the list property from state.
   */
  protected function computedListProperty() {
    /** @var \Drupal\image\Plugin\Field\FieldType\ImageItem $image_item */
    $image_item = $this->getParent();

    /** @var \Drupal\file\FileInterface $file */
    $file = $image_item->entity;
    $width = $image_item->width;
    $height = $image_item->height;

    foreach (ImageStyle::loadMultiple() as $style) {
      $include_image_style = !empty($style->include_in_normalized_entity);

      try {
        $granularity = $style->normalized_entity_granularity;
        if (is_array($granularity)) {
          /** @var \Drupal\Core\entity $entity */
          $entity = $image_item->getParent()->getParent()->getValue();
          if ($entity) {
            $entity_type = $entity->getEntityTypeId();
            $bundle = $entity->bundle;
            if (empty($entity_type) || empty($bundle) || !in_array($entity_type, array_keys($granularity)) || !in_array($bundle, $granularity[$entity_type])) {
              $include_image_style = FALSE;
            }
          }
        }
      }
      catch (Exception $e) {
        // No parent entity was found, this is fine.
      }

      if ($include_image_style) {
        $this->list[$style->getName()] = $this->createItem($style->getName(), $this->computeImageStyleMetadata($file, $width, $height, $style));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function createItem($offset = 0, $value = NULL) {
    // @todo perhaps some override like \Drupal\Core\Field\FieldItemList::createItem()?
    return parent::createItem($offset, $value);
  }


  /**
   * @param \Drupal\file\FileInterface $file
   * @param $width
   * @param $height
   * @param \Drupal\image\ImageStyleInterface $style
   * @return array|bool
   *
   * @todo rather than returning an array, return a value object that implements CacheableDependencyInterface
   *
   * @see \Drupal\image\Entity\ImageStyle::buildUrl
   * -> tag: config:image.settings
   *
   * -> tag: $style->getCacheTags()
   */
  protected function computeImageStyleMetadata(FileInterface $file, $width, $height, ImageStyleInterface $style) {
    $file_uri = $file->getFileUri();

    if (!$style->supportsUri($file_uri)) {
      return NULL;
    }

    $dimensions = [
      'width' => $width,
      'height' => $height,
    ];
    $style->transformDimensions($dimensions, $file_uri);

    return [
      'url' => file_url_transform_relative($style->buildUrl($file_uri)),
      'width' => $dimensions['width'],
      'height' => $dimensions['height'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function get($index) {
    $this->computedListProperty();
    return isset($this->list[$index]) ? $this->list[$index] : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getIterator() {
    $this->computedListProperty();
    return parent::getIterator();
  }

  /**
   * {@inheritdoc}
   */
  public function getValue() {
    $this->computedListProperty();
    return parent::getValue();
  }

}
