<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Setting\Advanced\IncludeDeprecated.
 */

namespace Drupal\bootstrap_lite\Plugin\Setting\Advanced;

use Drupal\bootstrap_lite\Annotation\BootstrapSetting;
use Drupal\bootstrap_lite\Plugin\Setting\SettingBase;
use Drupal\Core\Annotation\Translation;

/**
 * The "include_deprecated" theme setting.
 *
 * @ingroup plugins_setting
 *
 * @BootstrapSetting(
 *   id = "include_deprecated",
 *   type = "checkbox",
 *   weight = -3,
 *   title = @Translation("Include deprecated functions"),
 *   defaultValue = 0,
 *   description = @Translation("Enabling this setting will include any <code>deprecated.php</code> file found in your theme or base themes."),
 *   groups = {
 *     "advanced" = @Translation("Advanced"),
 *   },
 * )
 */
class IncludeDeprecated extends SettingBase {}
