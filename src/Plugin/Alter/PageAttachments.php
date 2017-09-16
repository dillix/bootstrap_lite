<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Alter\PageAttachments.
 */

namespace Drupal\bootstrap_lite\Plugin\Alter;

use Drupal\bootstrap_lite\Annotation\BootstrapAlter;
use Drupal\bootstrap_lite\Plugin\PluginBase;

/**
 * Implements hook_page_attachments_alter().
 *
 * @ingroup plugins_alter
 *
 * @BootstrapAlter("page_attachments")
 */
class PageAttachments extends PluginBase implements AlterInterface {

  /**
   * {@inheritdoc}
   */
  public function alter(&$attachments, &$context1 = NULL, &$context2 = NULL) {
    if ($this->theme->livereloadUrl()) {
      $attachments['#attached']['library'][] = 'bootstrap_lite/livereload';
    }
    if ($this->theme->getSetting('popover_enabled')) {
      $attachments['#attached']['library'][] = 'bootstrap_lite/popover';
    }
    if ($this->theme->getSetting('tooltip_enabled')) {
      $attachments['#attached']['library'][] = 'bootstrap_lite/tooltip';
    }
    $attachments['#attached']['drupalSettings']['bootstrap_lite'] = $this->theme->drupalSettings();
  }

}
