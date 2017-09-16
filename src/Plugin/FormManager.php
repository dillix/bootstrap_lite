<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\FormManager.
 */

namespace Drupal\bootstrap_lite\Plugin;

use Drupal\bootstrap_lite\Theme;

/**
 * Manages discovery and instantiation of Bootstrap form alters.
 *
 * @ingroup plugins_form
 */
class FormManager extends PluginManager {

  /**
   * Constructs a new \Drupal\bootstrap_lite\Plugin\FormManager object.
   *
   * @param \Drupal\bootstrap_lite\Theme $theme
   *   The theme to use for discovery.
   */
  public function __construct(Theme $theme) {
    parent::__construct($theme, 'Plugin/Form', 'Drupal\bootstrap_lite\Plugin\Form\FormInterface', 'Drupal\bootstrap_lite\Annotation\BootstrapForm');
    $this->setCacheBackend(\Drupal::cache('discovery'), 'theme:' . $theme->getName() . ':form', $this->getCacheTags());
  }

}
