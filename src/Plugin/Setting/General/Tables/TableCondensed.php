<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Setting\General\Tables\TableCondensed.
 */

namespace Drupal\bootstrap_lite\Plugin\Setting\General\Tables;

use Drupal\bootstrap_lite\Annotation\BootstrapSetting;
use Drupal\bootstrap_lite\Plugin\Setting\SettingBase;
use Drupal\Core\Annotation\Translation;

/**
 * The "table_condensed" theme setting.
 *
 * @ingroup plugins_setting
 *
 * @BootstrapSetting(
 *   id = "table_condensed",
 *   type = "checkbox",
 *   title = @Translation("Condensed table"),
 *   description = @Translation("Make tables more compact by cutting cell padding in half."),
 *   defaultValue = 0,
 *   groups = {
 *     "general" = @Translation("General"),
 *     "tables" = @Translation("Tables"),
 *   },
 * )
 */
class TableCondensed extends SettingBase {}
