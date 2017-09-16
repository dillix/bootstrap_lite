<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Setting\General\Tables\TableHover.
 */

namespace Drupal\bootstrap_lite\Plugin\Setting\General\Tables;

use Drupal\bootstrap_lite\Annotation\BootstrapSetting;
use Drupal\bootstrap_lite\Plugin\Setting\SettingBase;
use Drupal\Core\Annotation\Translation;

/**
 * The "table_hover" theme setting.
 *
 * @ingroup plugins_setting
 *
 * @BootstrapSetting(
 *   id = "table_hover",
 *   type = "checkbox",
 *   title = @Translation("Hover rows"),
 *   description = @Translation("Enable a hover state on table rows."),
 *   defaultValue = 1,
 *   groups = {
 *     "general" = @Translation("General"),
 *     "tables" = @Translation("Tables"),
 *   },
 * )
 */
class TableHover extends SettingBase {}
