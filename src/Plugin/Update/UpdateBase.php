<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Update\UpdateBase.
 */

namespace Drupal\bootstrap_lite\Plugin\Update;

use Drupal\bootstrap_lite\BootstrapLite;
use Drupal\bootstrap_lite\Plugin\PluginBase;
use Drupal\bootstrap_lite\Theme;

/**
 * Base class for an update.
 *
 * @ingroup plugins_update
 */
class UpdateBase extends PluginBase implements UpdateInterface {

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return isset($this->pluginDefinition['description']) ? $this->pluginDefinition['description'] : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return !empty($this->pluginDefinition['label']) ? $this->pluginDefinition['label'] : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getProvider() {
    return isset($this->pluginDefinition['provider']) ? $this->pluginDefinition['provider'] : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getSchema() {
    return (int) $this->getPluginId();
  }

  /**
   * {@inheritdoc}
   */
  public function getSeverity() {
    return isset($this->pluginDefinition['severity']) ? $this->pluginDefinition['severity'] : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getTheme() {
    return BootstrapLite::getTheme($this->pluginDefinition['provider']);
  }

  /**
   * {@inheritdoc}
   */
  public function isPrivate() {
    return !empty($this->pluginDefinition['private']);
  }

  /**
   * {@inheritdoc}
   */
  public function process(Theme $theme, array &$context) {}

}
