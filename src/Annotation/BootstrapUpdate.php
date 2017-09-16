<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Annotation\BootstrapUpdate.
 */

namespace Drupal\bootstrap_lite\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a BootstrapUpdate annotation object.
 *
 * Plugin Namespace: "Plugin/Update".
 *
 * @see \Drupal\bootstrap_lite\Plugin\UpdateInterface
 * @see \Drupal\bootstrap_lite\Plugin\UpdateManager
 * @see plugin_api
 *
 * @Annotation
 *
 * @ingroup plugins_update
 */
class BootstrapUpdate extends Plugin {

  /**
   * The schema version.
   *
   * @var int
   */
  public $id = '';

  /**
   * A short human-readable label.
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $label = '';

  /**
   * A detailed description.
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $description = '';

  /**
   * Level of severity. Should be one of: default, danger, info, warning.
   *
   * @var string
   */
  public $severity = 'default';

  /**
   * Indicates whether or not the update should apply only to itself
   * (the theme that implemented the plugin) and none of its sub-themes.
   *
   * @var bool
   */
  public $private = FALSE;


}
