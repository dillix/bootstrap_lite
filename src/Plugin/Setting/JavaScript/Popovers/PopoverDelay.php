<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Setting\JavaScript\Popovers\PopoverDelay.
 */

namespace Drupal\bootstrap_lite\Plugin\Setting\JavaScript\Popovers;

use Drupal\bootstrap_lite\Annotation\BootstrapSetting;
use Drupal\bootstrap_lite\Plugin\Setting\SettingBase;
use Drupal\Core\Annotation\Translation;

/**
 * The "popover_delay" theme setting.
 *
 * @ingroup plugins_setting
 *
 * @BootstrapSetting(
 *   id = "popover_delay",
 *   type = "textfield",
 *   title = @Translation("delay"),
 *   description = @Translation("The amount of time to delay showing and hiding the popover (in milliseconds). Does not apply to manual trigger type."),
 *   defaultValue = "0",
 *   groups = {
 *     "javascript" = @Translation("JavaScript"),
 *     "popovers" = @Translation("Popovers"),
 *     "options" = @Translation("Options"),
 *   },
 * )
 */
class PopoverDelay extends SettingBase {

  /**
   * {@inheritdoc}
   */
  public function drupalSettings() {
    return !!$this->theme->getSetting('popover_enabled');
  }

}
