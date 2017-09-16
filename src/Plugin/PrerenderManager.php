<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\PrerenderManager.
 */

namespace Drupal\bootstrap_lite\Plugin;

use Drupal\bootstrap_lite\Theme;
use Drupal\bootstrap_lite\Utility\Element;

/**
 * Manages discovery and instantiation of Bootstrap Lite pre-render callbacks.
 *
 * @ingroup plugins_prerender
 */
class PrerenderManager extends PluginManager {

  /**
   * Constructs a new \Drupal\bootstrap_lite\Plugin\PrerenderManager object.
   *
   * @param \Drupal\bootstrap_lite\Theme $theme
   *   The theme to use for discovery.
   */
  public function __construct(Theme $theme) {
    parent::__construct($theme, 'Plugin/Prerender', 'Drupal\bootstrap_lite\Plugin\Prerender\PrerenderInterface', 'Drupal\bootstrap_lite\Annotation\BootstrapPrerender');
    $this->setCacheBackend(\Drupal::cache('discovery'), 'theme:' . $theme->getName() . ':prerender', $this->getCacheTags());
  }

  /**
   * Pre-render render array element callback.
   *
   * @param array $element
   *   The render array element.
   *
   * @return array
   *   The modified render array element.
   */
  public static function preRender(array $element) {
    if (!empty($element['#bootstrap_lite_ignore_pre_render'])) {
      return $element;
    }

    $e = Element::create($element);

    if ($e->isType('machine_name')) {
      $e->addClass('form-inline', 'wrapper_attributes');
    }

    // Add smart descriptions to the element, if necessary.
    $e->smartDescription();

    return $element;
  }

}
