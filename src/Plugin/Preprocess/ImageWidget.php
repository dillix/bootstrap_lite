<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Preprocess\ImageWidget.
 */

namespace Drupal\bootstrap_lite\Plugin\Preprocess;

use Drupal\bootstrap_lite\Annotation\BootstrapPreprocess;
use Drupal\bootstrap_lite\Utility\Element;
use Drupal\bootstrap_lite\Utility\Variables;

/**
 * Pre-processes variables for the "image_widget" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @see image-widget.html.twig
 *
 * @BootstrapPreprocess("image_widget",
 *   replace = "template_preprocess_image_widget"
 * )
 */
class ImageWidget extends PreprocessBase implements PreprocessInterface {

  /**
   * {@inheritdoc}
   */
  public function preprocessElement(Element $element, Variables $variables) {
    $variables->addClass(['image-widget', 'js-form-managed-file', 'form-managed-file', 'clearfix']);

    $data = &$variables->offsetGet('data', []);
    foreach ($element->children() as $key => $child) {
      $data[$key] = $child->getArray();
    }
  }

}
