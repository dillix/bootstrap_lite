<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Annotation\PluginCallback.
 */

namespace Drupal\bootstrap_lite\Annotation;

use Drupal\bootstrap_lite\BootstrapLite;
use Drupal\Component\Annotation\AnnotationInterface;
use Drupal\Component\Annotation\PluginID;

/**
 * Defines a Plugin annotation object that just contains an ID.
 *
 * @Annotation
 *
 * @ingroup utility
 */
class PluginCallback extends PluginID {

  /**
   * The plugin ID.
   *
   * When an annotation is given no key, 'value' is assumed by Doctrine.
   *
   * @var string
   */
  public $value;

  /**
   * Flag that determines how to add the plugin to a callback array.
   *
   * Must be one of the following constants:
   *   - \Drupal\bootstrap_lite\BootstrapLite::CALLBACK_APPEND
   *   - \Drupal\bootstrap_lite\BootstrapLite::CALLBACK_PREPEND
   *   - \Drupal\bootstrap_lite\BootstrapLite::CALLBACK_REPLACE_APPEND
   *   - \Drupal\bootstrap_lite\BootstrapLite::CALLBACK_REPLACE_PREPEND
   * Use with @ BootstrapConstant annotation.
   *
   * @see \Drupal\bootstrap_lite\BootstrapLite::addCallback()
   *
   * @var \Drupal\bootstrap_lite\Annotation\BootstrapConstant
   */
  public $action = BootstrapLite::CALLBACK_APPEND;

  /**
   * A callback to replace.
   *
   * @var string
   */
  public $replace = FALSE;

  /**
   * {@inheritdoc}
   */
  public function get() {
    $definition = parent::get();
    $parent_properties = array_keys($definition);
    $parent_properties[] = 'value';

    // Merge in the defined properties.
    $reflection = new \ReflectionClass($this);
    foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
      $name = $property->getName();
      if (in_array($name, $parent_properties)) {
        continue;
      }
      $value = $property->getValue($this);
      if ($value instanceof AnnotationInterface) {
        $value = $value->get();
      }
      $definition[$name] = $value;
    }

    return $definition;
  }

}
