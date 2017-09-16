<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Setting\General\Container\FluidContainer.
 */

namespace Drupal\bootstrap_lite\Plugin\Setting\General\Container;

use Drupal\bootstrap_lite\Annotation\BootstrapSetting;
use Drupal\bootstrap_lite\Plugin\Setting\SettingBase;
use Drupal\Core\Annotation\Translation;

/**
 * Container theme settings.
 *
 * @ingroup plugins_setting
 *
 * @BootstrapSetting(
 *   id = "fluid_container",
 *   type = "checkbox",
 *   title = @Translation("Fluid container"),
 *   defaultValue = 0,
 *   description = @Translation("Uses the <code>.container-fluid</code> class instead of <code>.container</code>."),
 *   groups = {
 *     "general" = @Translation("General"),
 *     "container" = @Translation("Container"),
 *   },
 *   see = {
 *     "http://getbootstrap.com/css/#grid-example-fluid" = @Translation("Fluid container"),
 *   },
 * )
 */
class FluidContainer extends SettingBase {}
