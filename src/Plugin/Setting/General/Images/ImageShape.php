<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Setting\General\Images\ImageShape.
 */

namespace Drupal\bootstrap_lite\Plugin\Setting\General\Images;

use Drupal\bootstrap_lite\Annotation\BootstrapSetting;
use Drupal\bootstrap_lite\Plugin\Setting\SettingBase;
use Drupal\Core\Annotation\Translation;

/**
 * The "image_shape" theme setting.
 *
 * @ingroup plugins_setting
 *
 * @BootstrapSetting(
 *   id = "image_shape",
 *   type = "select",
 *   title = @Translation("Default image shape"),
 *   description = @Translation("Add classes to an <code>&lt;img&gt;</code> element to easily style images in any project."),
 *   defaultValue = "",
 *   empty_option = @Translation("None"),
 *   groups = {
 *     "general" = @Translation("General"),
 *     "images" = @Translation("Images"),
 *   },
 *   options = {
 *     "rounded" = @Translation("Rounded"),
 *     "rounded-circle" = @Translation("Circle"),
 *     "img-thumbnail" = @Translation("Thumbnail"),
 *   },
 *   see = {
 *     "https://getbootstrap.com/docs/4.0/content/images/" = @Translation("Image Shapes"),
 *   },
 * )
 */
class ImageShape extends SettingBase {}
