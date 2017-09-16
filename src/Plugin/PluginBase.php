<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\PluginBase.
 */

namespace Drupal\bootstrap_lite\Plugin;

use Drupal\bootstrap_lite\BootstrapLite;

/**
 * Base class for an update.
 *
 * @ingroup utility
 */
class PluginBase extends \Drupal\Core\Plugin\PluginBase {

  /**
   * The currently set theme object.
   *
   * @var \Drupal\bootstrap_lite\Theme
   */
  protected $theme;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    if (!isset($configuration['theme'])) {
      $configuration['theme'] = BootstrapLite::getTheme();
    }
    $this->theme = $configuration['theme'];
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

}
