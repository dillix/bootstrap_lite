<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Preprocess\Page.
 */

namespace Drupal\bootstrap_lite\Plugin\Preprocess;

use Drupal\bootstrap_lite\Annotation\BootstrapPreprocess;
use Drupal\bootstrap_lite\Utility\Variables;

/**
 * Pre-processes variables for the "page" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("page")
 */
class Page extends PreprocessBase implements PreprocessInterface {

  /**
   * {@inheritdoc}
   */
  public function preprocessVariables(Variables $variables) {
    // Setup default attributes.
    $variables->getAttributes($variables::NAVBAR);
    $variables->getAttributes($variables::HEADER);
    $variables->getAttributes($variables::CONTENT);
    $variables->getAttributes($variables::FOOTER);
    $this->preprocessAttributes();
  }

}
