<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\SettingManager.
 */

namespace Drupal\bootstrap_lite\Plugin;

use Drupal\bootstrap_lite\Theme;
use Drupal\Component\Utility\SortArray;

/**
 * Manages discovery and instantiation of Bootstrap Lite theme settings.
 *
 * @ingroup plugins_setting
 */
class SettingManager extends PluginManager {

  /**
   * Constructs a new \Drupal\bootstrap_lite\Plugin\SettingManager object.
   *
   * @param \Drupal\bootstrap_lite\Theme $theme
   *   The theme to use for discovery.
   */
  public function __construct(Theme $theme) {
    parent::__construct($theme, 'Plugin/Setting', 'Drupal\bootstrap_lite\Plugin\Setting\SettingInterface', 'Drupal\bootstrap_lite\Annotation\BootstrapSetting');
    $this->setCacheBackend(\Drupal::cache('discovery'), 'theme:' . $theme->getName() . ':setting', $this->getCacheTags());
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinitions($sorted = TRUE) {
    $definitions = parent::getDefinitions(FALSE);
    if ($sorted) {
      $groups = [];
      foreach ($definitions as $plugin_id => $definition) {
        $key = !empty($definition['groups']) ? implode(':', array_keys($definition['groups'])) : '_default';
        $groups[$key][$plugin_id] = $definition;
      }
      ksort($groups);
      $definitions = [];
      foreach ($groups as $settings) {
        uasort($settings, [$this, 'sort']);
        $definitions = array_merge($definitions, $settings);

      }
    }
    return $definitions;
  }

  /**
   * Sorts a structured array by either a set 'weight' property or by the ID.
   *
   * @param array $a
   *   First item for comparison.
   * @param array $b
   *   Second item for comparison.
   *
   * @return int
   *   The comparison result for uasort().
   */
  public static function sort(array $a, array $b) {
    if (isset($a['weight']) || isset($b['weight'])) {
      return SortArray::sortByWeightElement($a, $b);
    }
    else {
      return SortArray::sortByKeyString($a, $b, 'id');
    }
  }

}
