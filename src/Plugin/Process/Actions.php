<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Process\Actions.
 */

namespace Drupal\bootstrap_lite\Plugin\Process;

use Drupal\bootstrap_lite\Annotation\BootstrapProcess;
use Drupal\bootstrap_lite\Utility\Element;
use Drupal\Core\Form\FormStateInterface;

/**
 * Processes the "actions" element.
 *
 * @ingroup plugins_process
 *
 * @BootstrapProcess("actions")
 */
class Actions extends ProcessBase implements ProcessInterface {

  /**
   * {@inheritdoc}
   */
  public static function processElement(Element $element, FormStateInterface $form_state, array &$complete_form) {
    foreach ($element->children() as $child) {
      if ($child->isPropertyEmpty('icon')) {
        $child->setIcon();
      }
    }
  }

}
