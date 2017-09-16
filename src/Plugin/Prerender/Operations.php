<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Prerender\Operations.
 */

namespace Drupal\bootstrap_lite\Plugin\Prerender;

use Drupal\bootstrap_lite\Annotation\BootstrapPrerender;

/**
 * Pre-render callback for the "operations" element type.
 *
 * @ingroup plugins_prerender
 *
 * @BootstrapPrerender("operations",
 *   replace = "Drupal\Core\Render\Element\Operations::preRenderDropbutton"
 * )
 *
 * @see \Drupal\bootstrap_lite\Plugin\Prerender\Dropbutton
 * @see \Drupal\Core\Render\Element\Operations::preRenderDropbutton()
 */
class Operations extends Dropbutton {}
