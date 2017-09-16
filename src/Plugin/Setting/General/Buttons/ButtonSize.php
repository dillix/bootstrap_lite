<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Setting\General\Buttons\ButtonSize.
 */

namespace Drupal\bootstrap_lite\Plugin\Setting\General\Buttons;

use Drupal\bootstrap_lite\Annotation\BootstrapSetting;
use Drupal\bootstrap_lite\Plugin\Setting\SettingBase;
use Drupal\Core\Annotation\Translation;

/**
 * The "button_size" theme setting.
 *
 * @ingroup plugins_setting
 *
 * @BootstrapSetting(
 *   id = "button_size",
 *   type = "select",
 *   title = @Translation("Default button size"),
 *   defaultValue = "",
 *   description = @Translation("Defines the Bootstrap Buttons specific size"),
 *   empty_option = @Translation("Normal"),
 *   groups = {
 *     "general" = @Translation("General"),
 *     "button" = @Translation("Buttons"),
 *   },
 *   options = {
 *     "btn-xs" = @Translation("Extra Small"),
 *     "btn-sm" = @Translation("Small"),
 *     "btn-lg" = @Translation("Large"),
 *   },
 * )
 */
class ButtonSize extends SettingBase {}
