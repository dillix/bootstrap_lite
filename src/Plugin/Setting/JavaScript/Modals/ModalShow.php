<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Setting\JavaScript\Modals\ModalShow.
 */

namespace Drupal\bootstrap_lite\Plugin\Setting\JavaScript\Modals;

use Drupal\bootstrap_lite\Annotation\BootstrapSetting;
use Drupal\bootstrap_lite\Plugin\Setting\SettingBase;
use Drupal\Core\Annotation\Translation;

/**
 * The "modal_show" theme setting.
 *
 * @ingroup plugins_setting
 *
 * @BootstrapSetting(
 *   id = "modal_show",
 *   type = "checkbox",
 *   title = @Translation("show"),
 *   description = @Translation("Shows the modal when initialized."),
 *   defaultValue = 1,
 *   groups = {
 *     "javascript" = @Translation("JavaScript"),
 *     "modals" = @Translation("Modals"),
 *     "options" = @Translation("Options"),
 *   },
 * )
 */
class ModalShow extends SettingBase {

  /**
   * {@inheritdoc}
   */
  public function drupalSettings() {
    return !!$this->theme->getSetting('modal_enabled');
  }

}
