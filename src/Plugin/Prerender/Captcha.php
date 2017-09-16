<?php

namespace Drupal\bootstrap_lite\Plugin\Prerender;

use Drupal\bootstrap_lite\Annotation\BootstrapPrerender;
use Drupal\bootstrap_lite\Utility\Element;

/**
 * Pre-render callback for the "captcha" element type.
 *
 * @ingroup plugins_prerender
 *
 * @BootstrapPrerender("captcha",
 *   action = @BootstrapConstant(
 *     "\Drupal\bootstrap_lite\BootstrapLite::CALLBACK_PREPEND"
 *   )
 * )
 */
class Captcha extends PrerenderBase {

  /**
   * {@inheritdoc}
   */
  public static function preRenderElement(Element $element) {
    parent::preRenderElement($element);
    $element->setProperty('smart_description', FALSE, TRUE);
  }

}
