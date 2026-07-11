<?php
/**
 * @file
 * Contains \Drupal\bootstrap_lite\Plugin\Alter\LibraryInfo.
 */

namespace Drupal\bootstrap_lite\Plugin\Alter;

use Drupal\bootstrap_lite\Annotation\BootstrapAlter;
use Drupal\bootstrap_lite\BootstrapLite;
use Drupal\bootstrap_lite\Plugin\PluginBase;
use Drupal\Component\Utility\NestedArray;

/**
 * Implements hook_library_info_alter().
 *
 * @ingroup plugins_alter
 *
 * @BootstrapAlter("library_info")
 */
class LibraryInfo extends PluginBase implements AlterInterface {

  /**
   * {@inheritdoc}
   */
  public function alter(&$libraries, &$extension = NULL, &$context2 = NULL) {
    $livereload = $this->theme->livereloadUrl();

    // Disable preprocess on all CSS/JS if "livereload" is enabled.
    if ($livereload) {
      $this->processLibrary($libraries, function (&$info, &$key, $type) {
        if ($type === 'css' || $type === 'js') {
          $info['preprocess'] = FALSE;
        }
      });
    }

    if ($extension === 'bootstrap_lite') {
      // Alter the "livereload.js" placeholder with the correct URL.
      if ($livereload) {
        $libraries['livereload']['js'][$livereload] = $libraries['livereload']['js']['livereload.js'];
        unset($libraries['livereload']['js']['livereload.js']);
      }

      // Retrieve the theme's CDN provider and assets.
      $provider = $this->theme->getProvider();
      $assets = $provider ? $provider->getAssets() : [];

      // Immediately return if there is no provider or assets.
      if (!$provider || !$assets) {
        return;
      }

      // Merge the assets into the library info.
      $libraries['framework'] = NestedArray::mergeDeepArray([$assets, $libraries['framework']], TRUE);

      // Add a specific version and theme CSS overrides file.
      // @todo This should be retrieved by the Provider API.
      $version = $this->theme->getSetting('cdn_' . $provider->getPluginId() . '_version') ?: BootstrapLite::FRAMEWORK_VERSION;
      $libraries['framework']['version'] = $version;
      $provider_theme = $this->theme->getSetting('cdn_' . $provider->getPluginId() . '_theme') ?: 'bootstrap_lite';
      $provider_theme = $provider_theme === 'bootstrap_lite' || $provider_theme === 'bootstrap_lite_theme' ? '' : "-$provider_theme";

      foreach ($this->theme->getAncestry(TRUE) as $ancestor) {
        $overrides = $ancestor->getPath() . "/css/$version/overrides$provider_theme.min.css";
        if (file_exists($overrides)) {
          // Since this uses a relative path to the ancestor from DRUPAL_ROOT,
          // we must prepend the entire path with forward slash (/) so it
          // doesn't prepend the active theme's path.
          $overrides = "/$overrides";

          // The overrides file must also be stored in the "base" category so
          // it isn't added after any potential sub-theme's "theme" category.
          // There's no weight, so it will be added after the provider's assets.
          // @see https://www.drupal.org/node/2770613
          $libraries['framework']['css']['base'][$overrides] = [];
          break;
        }
      }
    }
    // Core replacements.
    elseif ($extension === 'core') {
      // Replace core dialog/jQuery UI implementations with Bootstrap Modals.
      if ($this->theme->getSetting('modal_enabled')) {
        // Replace dependencies if using bridge so jQuery UI is not loaded
        // and remove dialog.jquery-ui.js since the dialog widget isn't loaded.
        if ($this->theme->getSetting('modal_jquery_ui_bridge')) {
          // Remove core's jquery.ui.dialog dependency (merged away in D10, so
          // this is usually a no-op, but harmless to keep for older minors).
          $key = array_search('core/jquery.ui.dialog', $libraries['drupal.dialog']['dependencies']);
          if ($key !== FALSE) {
            unset($libraries['drupal.dialog']['dependencies'][$key]);
          }

          // The bridge provides its own $.fn.dialog backed by a Bootstrap
          // modal, so strip core's jQuery UI dialog implementation entirely:
          // its glue (dialog.jquery-ui.js / dialog.position.js) and the jQuery
          // UI widget assets now vendored directly inside core/drupal.dialog.
          // Leaving the dialog widget in place would register a competing
          // $.fn.dialog and make the bridge's createPlugin('dialog') fatal.
          $strip_js = [
            'misc/dialog/dialog.jquery-ui.js',
            'misc/dialog/dialog.position.js',
            'assets/vendor/jquery.ui/ui/widgets/dialog-min.js',
            'assets/vendor/jquery.ui/ui/widgets/button-min.js',
            'assets/vendor/jquery.ui/ui/widgets/checkboxradio-min.js',
            'assets/vendor/jquery.ui/ui/widgets/draggable-min.js',
            'assets/vendor/jquery.ui/ui/widgets/resizable-min.js',
            'assets/vendor/jquery.ui/ui/widgets/controlgroup-min.js',
            'assets/vendor/jquery.ui/ui/widgets/mouse-min.js',
            'assets/vendor/jquery.ui/ui/form-reset-mixin-min.js',
            // ckeditor5_library_info_alter() injects this directly into
            // core/drupal.dialog's own js list (not via libraries-extend), to
            // override jQuery UI dialog's `_allowInteraction()` focus trap so
            // CKEditor 5 balloons inside a dialog keep focus. It's specific to
            // the jQuery UI dialog widget we just stripped above ($.ui.dialog
            // no longer exists once dialog-min.js is gone) and moot for the
            // Bootstrap-modal bridge, which has no focus-trap/balloon conflict
            // of its own. Left in place, it fatals at file-execution time
            // (`$.widget('ui.dialog', $.ui.dialog, ...)` on undefined).
            'modules/ckeditor5/js/ckeditor5.dialog.fix.js',
          ];
          foreach ($strip_js as $file) {
            unset($libraries['drupal.dialog']['js'][$file]);
          }

          // Strip the jQuery UI base-theme CSS that styled the dialog widget.
          $strip_css = [
            'assets/vendor/jquery.ui/themes/base/dialog.css',
            'assets/vendor/jquery.ui/themes/base/button.css',
            'assets/vendor/jquery.ui/themes/base/checkboxradio.css',
            'assets/vendor/jquery.ui/themes/base/resizable.css',
            'assets/vendor/jquery.ui/themes/base/controlgroup.css',
            // Companion CSS to the ckeditor5.dialog.fix.js stripped above
            // (z-index rule between CKEditor 5 and jQuery UI); not fatal like
            // its JS counterpart, but equally moot without the jQuery UI
            // dialog widget.
            'modules/ckeditor5/css/ckeditor5.dialog.fix.css',
          ];
          foreach ($strip_css as $file) {
            unset($libraries['drupal.dialog']['css']['component'][$file]);
          }

          // Attach the bridge to core/drupal.dialog itself (not just the ajax
          // layer) so $.fn.dialog is registered for every front-end consumer of
          // the dialog library, whether or not they also load
          // core/drupal.dialog.ajax. Load order relative to dialog.js no longer
          // matters: the bridge registers $.fn.dialog at eval time and no
          // longer writes into drupalSettings.dialog.
          $libraries['drupal.dialog']['dependencies'][] = 'bootstrap_lite/modal.jquery.ui.bridge';
        }
        // Otherwise, just append the modal.
        else {
          $libraries['drupal.dialog']['dependencies'][] = 'bootstrap_lite/modal';
        }
      }
    }
  }

  /**
   * Processes library definitions.
   *
   * @param array $libraries
   *   The libraries array, passed by reference.
   * @param callable $callback
   *   The callback to perform processing on the library.
   */
  public function processLibrary(&$libraries, callable $callback) {
    foreach ($libraries as &$library) {
      foreach ($library as $type => $definition) {
        if (is_array($definition)) {
          $modified = [];
          // CSS needs special handling since it contains grouping.
          if ($type === 'css') {
            foreach ($definition as $group => $files) {
              foreach ($files as $key => $info) {
                call_user_func_array($callback, [&$info, &$key, $type]);
                $modified[$group][$key] = $info;
              }
            }
          }
          else {
            foreach ($definition as $key => $info) {
              call_user_func_array($callback, [&$info, &$key, $type]);
              $modified[$key] = $info;
            }
          }
          $library[$type] = $modified;
        }
      }
    }
  }

}
