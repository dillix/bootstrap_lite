<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Setting\Advanced\Cdn\CdnCustomJs.
 */

namespace Drupal\bootstrap_lite\Plugin\Setting\Advanced\Cdn;

use Drupal\bootstrap_lite\Annotation\BootstrapSetting;
use Drupal\bootstrap_lite\Plugin\Setting\SettingBase;
use Drupal\Core\Annotation\Translation;

/**
 * The "cdn_custom_js" theme setting.
 *
 * @ingroup plugins_setting
 *
 * @BootstrapSetting(
 *   cdn_provider = "custom",
 *   id = "cdn_custom_js",
 *   type = "textfield",
 *   weight = 3,
 *   title = @Translation("Bootstrap JavaScript URL"),
 *   defaultValue = "https://cdn.jsdelivr.net/bootstrap/4.0.0/js/bootstrap.js",
 *   description = @Translation("It is best to use <code>https</code> protocols here as it will allow more flexibility if the need ever arises."),
 *   groups = {
 *     "advanced" = @Translation("Advanced"),
 *     "cdn" = @Translation("CDN (Content Delivery Network)"),
 *     "custom" = false,
 *   },
 * )
 */
class CdnCustomJs extends SettingBase {

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return ['library_info'];
  }

}
