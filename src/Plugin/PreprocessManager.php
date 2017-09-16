<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\PreprocessManager.
 */

namespace Drupal\bootstrap_lite\Plugin;

use Drupal\bootstrap_lite\Theme;

/**
 * Manages discovery and instantiation of Bootstrap Lite preprocess hooks.
 *
 * @ingroup plugins_preprocess
 */
class PreprocessManager extends PluginManager {

  /**
   * Constructs a new \Drupal\bootstrap_lite\Plugin\PreprocessManager object.
   *
   * @param \Drupal\bootstrap_lite\Theme $theme
   *   The theme to use for discovery.
   */
  public function __construct(Theme $theme) {
    parent::__construct($theme, 'Plugin/Preprocess', 'Drupal\bootstrap_lite\Plugin\Preprocess\PreprocessInterface', 'Drupal\bootstrap_lite\Annotation\BootstrapPreprocess');
    $this->setCacheBackend(\Drupal::cache('discovery'), 'theme:' . $theme->getName() . ':preprocess', $this->getCacheTags());
  }

}
