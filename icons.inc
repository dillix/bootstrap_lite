<?php
/**
 * @file
 * Icon API support to provide Bootstrap Framework glyphicons.
 */

use Drupal\bootstrap_lite\BootstrapLite;

/**
 * Implements hook_icon_providers().
 */
function bootstrap_lite_icon_providers() {
  $providers['bootstrap_lite'] = [
    'title' => t('Bootstrap Lite'),
    'url' => 'http://getbootstrap.com/components/#glyphicons',
  ];
  return $providers;
}

/**
 * Implements hook_icon_bundles().
 */
function bootstrap_lite_icon_bundles() {
  $bundles = [];
  if (BootstrapLite::getTheme()->hasGlyphicons()) {
    $bundles['bootstrap_lite'] = [
      'render' => 'sprite',
      'provider' => 'bootstrap_lite',
      'title' => t('Bootstrap Lite'),
      'version' => t('Icons by Glyphicons'),
      'variations' => [
        'icon-white' => t('White'),
      ],
      'settings' => [
        'tag' => 'span',
      ],
      'icons' => BootstrapLite::glyphicons(),
    ];
  }
  return $bundles;
}
