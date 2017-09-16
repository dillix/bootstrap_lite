<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Prerender\Dropbutton.
 */

namespace Drupal\bootstrap_lite\Plugin\Prerender;

use Drupal\bootstrap_lite\Annotation\BootstrapPrerender;
use Drupal\bootstrap_lite\Utility\Element;

/**
 * Pre-render callback for the "dropbutton" element type.
 *
 * @ingroup plugins_prerender
 *
 * @BootstrapPrerender("dropbutton",
 *   replace = "Drupal\Core\Render\Element\Dropbutton::preRenderDropbutton"
 * )
 *
 * @see \Drupal\Core\Render\Element\Dropbutton::preRenderDropbutton()
 */
class Dropbutton extends PrerenderBase {

  /**
   * {@inheritdoc}
   */
  public static function preRenderElement(Element $element) {
    $element['#attached']['library'][] = 'bootstrap_lite/dropdown';

    // Enable targeted theming of specific dropbuttons (e.g., 'operations' or
    // 'operations__node').
    if ($subtype = $element->getProperty('subtype')) {
      $element->setProperty('theme', $element->getProperty('theme') . "__$subtype");
    }
  }

}
