<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Preprocess\FormElementLabel.
 */

namespace Drupal\bootstrap_lite\Plugin\Preprocess;

use Drupal\bootstrap_lite\Annotation\BootstrapPreprocess;
use Drupal\bootstrap_lite\Utility\Element;
use Drupal\bootstrap_lite\Utility\Variables;

/**
 * Pre-processes variables for the "form_element_label" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("form_element_label")
 */
class FormElementLabel extends PreprocessBase implements PreprocessInterface {

  /**
   * {@inheritdoc}
   */
  public function preprocessElement(Element $element, Variables $variables) {
    // Map the element properties.
    $variables->map(['attributes', 'is_checkbox', 'is_radio']);

    // Preprocess attributes.
    $this->preprocessAttributes();
  }

}
