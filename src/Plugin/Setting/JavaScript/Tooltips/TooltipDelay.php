<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Setting\JavaScript\Tooltips\TooltipDelay.
 */

namespace Drupal\bootstrap_lite\Plugin\Setting\JavaScript\Tooltips;

use Drupal\bootstrap_lite\Annotation\BootstrapSetting;
use Drupal\bootstrap_lite\Plugin\Setting\SettingBase;
use Drupal\Core\Annotation\Translation;

/**
 * The "tooltip_delay" theme setting.
 *
 * @ingroup plugins_setting
 *
 * @BootstrapSetting(
 *   id = "tooltip_delay",
 *   type = "textfield",
 *   title = @Translation("delay"),
 *   description = @Translation("The amount of time to delay showing and hiding the tooltip (in milliseconds). Does not apply to manual trigger type."),
 *   defaultValue = "0",
 *   groups = {
 *     "javascript" = @Translation("JavaScript"),
 *     "tooltips" = @Translation("Tooltips"),
 *     "options" = @Translation("Options"),
 *   },
 * )
 */
class TooltipDelay extends SettingBase {

  /**
   * {@inheritdoc}
   */
  public function drupalSettings() {
    return !!$this->theme->getSetting('tooltip_enabled');
  }

}
