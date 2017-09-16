<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Preprocess\BootstrapModal.
 */

namespace Drupal\bootstrap_lite\Plugin\Preprocess;

use Drupal\bootstrap_lite\Annotation\BootstrapPreprocess;
use Drupal\bootstrap_lite\Utility\Variables;
use Drupal\Component\Utility\Html;

/**
 * Pre-processes variables for the "bootstrap_lite_modal" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("bootstrap_lite_modal")
 */
class BootstrapModal extends PreprocessBase implements PreprocessInterface {

  /**
   * {@inheritdoc}
   */
  protected function preprocessVariables(Variables $variables) {
    // Immediately log an error and return if Bootstrap modals are not enabled.
    if (!$this->theme->getSetting('modal_enabled')) {
      \Drupal::logger('bootstrap_lite')->error(t('Bootstrap Lite modals are not enabled.'));
      return;
    }

    // Retrieve the ID, generating one if needed.
    $id = $variables->getAttribute('id', Html::getUniqueId($variables->offsetGet('id', 'bootstrap-lite-modal')));
    $variables->setAttribute('id', $id);
    unset($variables['id']);

    if ($variables->title) {
      $title_id = $variables->getAttribute('id', "$id--title", $variables::TITLE);
      $variables->setAttribute('id', $title_id, $variables::TITLE);
      $variables->setAttribute('aria-labelledby', $title_id);
    }

    // Use a provided modal size or retrieve the default theme setting.
    $variables->size = $variables->size ?: $this->theme->getSetting('modal_size');

    // Convert the description variable.
    $this->preprocessDescription();

    // Ensure all attributes are proper objects.
    $this->preprocessAttributes();
  }

}
