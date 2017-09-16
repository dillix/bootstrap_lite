<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Preprocess\Region.
 */

namespace Drupal\bootstrap_lite\Plugin\Preprocess;

use Drupal\bootstrap_lite\Annotation\BootstrapPreprocess;
use Drupal\bootstrap_lite\BootstrapLite;
use Drupal\bootstrap_lite\Utility\Variables;

/**
 * Pre-processes variables for the "region" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("region")
 */
class Region extends PreprocessBase implements PreprocessInterface {

  /**
   * {@inheritdoc}
   */
  public function preprocessVariables(Variables $variables) {
    $region = $variables['elements']['#region'];
    $variables['region'] = $region;
    $variables['content'] = $variables['elements']['#children'];

    // Help region.
    if ($region === 'help' && !empty($variables['content'])) {
      $variables['content'] = [
        'icon' => BootstrapLite::glyphicon('question-sign'),
        'content' => ['#markup' => $variables['content']],
      ];
      $variables->addClass(['alert', 'alert-info', 'messages', 'info']);
    }

    // Support for "well" classes in regions.
    static $region_wells;
    if (!isset($region_wells)) {
      $region_wells = $this->theme->getSetting('region_wells');
    }
    if (!empty($region_wells[$region])) {
      $variables->addClass($region_wells[$region]);
    }
  }

}
