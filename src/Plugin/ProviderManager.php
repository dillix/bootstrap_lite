<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\ProviderManager.
 */

namespace Drupal\bootstrap_lite\Plugin;

use Drupal\bootstrap_lite\Plugin\Provider\ProviderInterface;
use Drupal\bootstrap_lite\Theme;

/**
 * Manages discovery and instantiation of Bootstrap CDN providers.
 *
 * @ingroup plugins_provider
 */
class ProviderManager extends PluginManager {
  /**
   * The base file system path for CDN providers.
   *
   * @var string
   */
  const FILE_PATH = 'public://bootstrap_lite/provider';

  /**
   * Constructs a new \Drupal\bootstrap_lite\Plugin\ProviderManager object.
   *
   * @param \Drupal\bootstrap_lite\Theme $theme
   *   The theme to use for discovery.
   */
  public function __construct(Theme $theme) {
    parent::__construct($theme, 'Plugin/Provider', 'Drupal\bootstrap_lite\Plugin\Provider\ProviderInterface', 'Drupal\bootstrap_lite\Annotation\BootstrapProvider');
    $this->setCacheBackend(\Drupal::cache('discovery'), 'theme:' . $theme->getName() . ':provider', $this->getCacheTags());
  }

  /**
   * {@inheritdoc}
   */
  public function processDefinition(&$definition, $plugin_id) {
    parent::processDefinition($definition, $plugin_id);
    /** @var ProviderInterface $provider */
    $provider = new $definition['class'](['theme' => $this->theme], $plugin_id, $definition);
    $provider->processDefinition($definition, $plugin_id);
  }

}
