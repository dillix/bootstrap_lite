<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Process\ManagedFile.
 */

namespace Drupal\bootstrap_lite\Plugin\Process;

use Drupal\bootstrap_lite\Annotation\BootstrapProcess;
use Drupal\bootstrap_lite\Utility\Element;
use Drupal\Core\Form\FormStateInterface;

/**
 * Processes the "managed_file" element.
 *
 * @ingroup plugins_process
 *
 * @BootstrapProcess("managed_file")
 */
class ManagedFile extends ProcessBase implements ProcessInterface {

  /**
   * {@inheritdoc}
   */
  public static function processElement(Element $element, FormStateInterface $form_state, array &$complete_form) {
    $ajax_wrapper_id = $element->upload_button->getProperty('ajax')['wrapper'];
    if ($prefix = $element->getProperty('prefix')) {
      $prefix = preg_replace('/<div id="' . $ajax_wrapper_id . '">/', '<div id="' . $ajax_wrapper_id . '" class="form-group">', $prefix);
      $element->setProperty('prefix', $prefix);
    }
  }

}
