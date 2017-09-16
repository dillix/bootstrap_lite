<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Setting\General\Forms\FormsRequiredHasError.
 */

namespace Drupal\bootstrap_lite\Plugin\Setting\General\Forms;

use Drupal\bootstrap_lite\Annotation\BootstrapSetting;
use Drupal\bootstrap_lite\Plugin\Setting\SettingBase;
use Drupal\Core\Annotation\Translation;

/**
 * The "forms_required_has_error" theme setting.
 *
 * @ingroup plugins_setting
 *
 * @BootstrapSetting(
 *   id = "forms_required_has_error",
 *   type = "checkbox",
 *   title = @Translation("Make required elements display as an error"),
 *   defaultValue = 0,
 *   description = @Translation("If an element in a form is required, enabling this will always display the element with a <code>.has-error</code> class. This turns the element red and helps in usability for determining which form elements are required to submit the form."),
 *   groups = {
 *     "general" = @Translation("General"),
 *     "forms" = @Translation("Forms"),
 *   },
 * )
 */
class FormsRequiredHasError extends SettingBase {}
