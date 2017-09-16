<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Preprocess\BootstrapCarousel.
 */

namespace Drupal\bootstrap_lite\Plugin\Preprocess;

use Drupal\bootstrap_lite\Annotation\BootstrapPreprocess;
use Drupal\bootstrap_lite\BootstrapLite;
use Drupal\bootstrap_lite\Utility\Element;
use Drupal\bootstrap_lite\Utility\Variables;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Component\Utility\Html;
use Drupal\Core\Template\Attribute;
use Drupal\Core\Url;

/**
 * Pre-processes variables for the "bootstrap_lite_carousel" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("bootstrap_lite_carousel")
 */
class BootstrapCarousel extends PreprocessBase implements PreprocessInterface {

  /**
   * {@inheritdoc}
   */
  protected function preprocessVariables(Variables $variables) {
    // Retrieve the ID, generating one if needed.
    $id = $variables->getAttribute('id', Html::getUniqueId($variables->offsetGet('id', 'bootstrap-lite-carousel')));
    unset($variables['id']);

    // Build slides.
    foreach ($variables->slides as $key => &$slide) {
      if (!isset($slide['attributes'])) {
        $slide['attributes'] = [];
      }
      $slide['attributes'] = new Attribute($slide['attributes']);
    }

    // Build controls.
    if ($variables->controls) {
      $left_icon = BootstrapLite::glyphicon('chevron-left');
      $right_icon = BootstrapLite::glyphicon('chevron-right');
      $url = Url::fromUserInput("#$id");
      $variables->controls = [
        'left' => [
          '#type' => 'link',
          '#title' => new FormattableMarkup(Element::create($left_icon)->renderPlain() . '<span class="sr-only">@text</span>', ['@text' => t('Previous')]),
          '#url' => $url,
          '#attributes' => [
            'class' => ['left', 'carousel-control'],
            'role' => 'button',
            'data-slide' => 'prev',
          ],
        ],
        'right' => [
          '#type' => 'link',
          '#title' => new FormattableMarkup(Element::create($right_icon)->renderPlain() . '<span class="sr-only">@text</span>', ['@text' => t('Next')]),
          '#url' => $url,
          '#attributes' => [
            'class' => ['right', 'carousel-control'],
            'role' => 'button',
            'data-slide' => 'next',
          ],
        ],
      ];
    }

    // Build indicators.
    if ($variables->indicators) {
      $variables->indicators = [
        '#theme' => 'item_list__bootstrap_lite_carousel_indicators',
        '#list_type' => 'ol',
        '#items' => array_keys($variables->slides),
        '#context' => [
          'target' => "#$id",
          'start_index' => $variables->start_index,
        ],
      ];
    }

    // Ensure all attributes are proper objects.
    $this->preprocessAttributes();
  }

}
