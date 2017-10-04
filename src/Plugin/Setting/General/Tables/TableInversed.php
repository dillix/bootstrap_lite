<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Setting\General\Tables\TableInversed.
 */

namespace Drupal\bootstrap_lite\Plugin\Setting\General\Tables;

use Drupal\bootstrap_lite\Annotation\BootstrapSetting;
use Drupal\bootstrap_lite\Plugin\Setting\SettingBase;
use Drupal\Core\Annotation\Translation;

/**
 * The "table_inversed" theme setting.
 *
 * @ingroup plugins_setting
 *
 * @BootstrapSetting(
 *   id = "table_inversed",
 *   type = "checkbox",
 *   title = @Translation("Inversed table"),
 *   description = @Translation("Make tables with light text on dark backgrounds."),
 *   defaultValue = 0,
 *   groups = {
 *     "general" = @Translation("General"),
 *     "tables" = @Translation("Tables"),
 *   },
 * )
 */
class TableInversed extends SettingBase {}
