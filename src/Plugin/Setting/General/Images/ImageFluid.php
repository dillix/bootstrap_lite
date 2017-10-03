<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Setting\General\Images\ImageFluid.
 */

namespace Drupal\bootstrap_lite\Plugin\Setting\General\Images;

use Drupal\bootstrap_lite\Annotation\BootstrapSetting;
use Drupal\bootstrap_lite\Plugin\Setting\SettingBase;
use Drupal\Core\Annotation\Translation;

/**
 * The "image_fluid" theme setting.
 *
 * @ingroup plugins_setting
 *
 * @BootstrapSetting(
 *   id = "image_fluid",
 *   type = "checkbox",
 *   title = @Translation("Responsive Images"),
 *   description = @Translation("Images in Bootstrap 4 can be made responsive-friendly via the addition of the <code>.img-fluid</code> class. This applies <code>max-width: 100%;</code> and <code>height: auto;</code> to the image so that it scales nicely to the parent element."),
 *   defaultValue = 1,
 *   groups = {
 *     "general" = @Translation("General"),
 *     "images" = @Translation("Images"),
 *   },
 * )
 */
class ImageFluid extends SettingBase {}
