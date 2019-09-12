<?php

namespace Drupal\jsonapi_image_styles\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\TypedData\ComputedItemListTrait;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;

/**
 * Represents the computed image styles for a file entity.
 */
class ImageStyleNormalizedFieldItemList extends FieldItemList {

  use ComputedItemListTrait;

  /**
   * {@inheritdoc}
   */
  protected function computeValue() {
    $entity = $this->getEntity();
    $uri = ($entity instanceof File) ? $entity->getFileUri() : FALSE;
    if (!$entity->id() || !$uri) {
      return;
    }

    $offset = 0;
    // @TODO: Make it a configuration option to define image styles to expose.
    $styles = [
      'large',
      'thumbnail',
    ];

    foreach ($styles as $style) {

      $image_style = ImageStyle::load($style);
      $url = ($image_style instanceof ImageStyle) ? $image_style->buildUrl($uri) : $url = file_create_url($uri);

      $this->list[] = $this->createItem(
        $offset,
        [
          'url' => [$style => $url],
        ]
      );
      $offset++;
    }
  }

}
