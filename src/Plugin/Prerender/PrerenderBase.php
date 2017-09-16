<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Prerender\PrerenderBase.
 */

namespace Drupal\bootstrap_lite\Plugin\Prerender;

use Drupal\bootstrap_lite\Utility\Element;

/**
 * Defines the interface for an object oriented preprocess plugin.
 *
 * @ingroup plugins_prerender
 */
class PrerenderBase implements PrerenderInterface {

  /**
   * {@inheritdoc}
   */
  public static function preRender(array $element) {
    static::preRenderElement(Element::create($element));
    return $element;
  }

  /**
   * Pre-render element callback.
   *
   * @param \Drupal\bootstrap_lite\Utility\Element $element
   *   The element object.
   */
  public static function preRenderElement(Element $element) {}

}
