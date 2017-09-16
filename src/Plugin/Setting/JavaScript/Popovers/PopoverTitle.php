<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Setting\JavaScript\Popovers\PopoverTitle.
 */

namespace Drupal\bootstrap_lite\Plugin\Setting\JavaScript\Popovers;

use Drupal\bootstrap_lite\Annotation\BootstrapSetting;
use Drupal\bootstrap_lite\Plugin\Setting\SettingBase;
use Drupal\Core\Annotation\Translation;

/**
 * The "popover_title" theme setting.
 *
 * @ingroup plugins_setting
 *
 * @BootstrapSetting(
 *   id = "popover_title",
 *   type = "textfield",
 *   title = @Translation("title"),
 *   description = @Translation("Default title value if <code>title</code> attribute isn't present."),
 *   defaultValue = "",
 *   groups = {
 *     "javascript" = @Translation("JavaScript"),
 *     "popovers" = @Translation("Popovers"),
 *     "options" = @Translation("Options"),
 *   },
 * )
 */
class PopoverTitle extends SettingBase {

  /**
   * {@inheritdoc}
   */
  public function drupalSettings() {
    return !!$this->theme->getSetting('popover_enabled');
  }

}
