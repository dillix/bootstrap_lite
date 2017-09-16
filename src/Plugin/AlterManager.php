<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\AlterManager.
 */

namespace Drupal\bootstrap_lite\Plugin;

use Drupal\bootstrap_lite\Theme;

/**
 * Manages discovery and instantiation of Bootstrap Lite hook alters.
 *
 * @ingroup plugins_alter
 */
class AlterManager extends PluginManager {

  /**
   * Constructs a new \Drupal\bootstrap_lite\Plugin\AlterManager object.
   *
   * @param \Drupal\bootstrap_lite\Theme $theme
   *   The theme to use for discovery.
   */
  public function __construct(Theme $theme) {
    parent::__construct($theme, 'Plugin/Alter', 'Drupal\bootstrap_lite\Plugin\Alter\AlterInterface', 'Drupal\bootstrap_lite\Annotation\BootstrapAlter');
    $this->setCacheBackend(\Drupal::cache('discovery'), 'theme:' . $theme->getName() . ':alter', $this->getCacheTags());
  }

}
