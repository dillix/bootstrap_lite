/**
 * @file
 * Bootstrap Lite Tooltips.
 */

var Drupal = Drupal || {};

(function ($, Drupal, BootstrapLite) {
  "use strict";

  // Tooltip defaults, computed from the theme settings exposed on
  // drupalSettings (BootstrapLite.settings). These are all native Bootstrap 4
  // Tooltip option names, but they are read here rather than written onto the
  // constructor: Bootstrap 4's static defaults live on a getter-only
  // `Constructor.Default` and drupalSettings is available independent of
  // Bootstrap's own load timing.
  var tooltipSettings = BootstrapLite.settings || {};
  var tooltipDefaults = {
    animation: !!tooltipSettings.tooltip_animation,
    html: !!tooltipSettings.tooltip_html,
    placement: tooltipSettings.tooltip_placement,
    selector: tooltipSettings.tooltip_selector,
    trigger: tooltipSettings.tooltip_trigger,
    delay: parseInt(tooltipSettings.tooltip_delay, 10),
    container: tooltipSettings.tooltip_container
  };

  /**
   * Bootstrap Lite Tooltips.
   *
   * @todo This should really be properly delegated if selector option is set.
   */
  Drupal.behaviors.bootstrapLiteTooltips = {
    attach: function (context) {
      if (!$.fn.tooltip) {
        return;
      }
      var elements = $(context).find('[data-toggle="tooltip"]').toArray();
      for (var i = 0; i < elements.length; i++) {
        var $element = $(elements[i]);
        // Bootstrap 4's native defaults first, then the theme's tooltipDefaults,
        // then per-element data attributes. jQuery's $.extend skips undefined
        // source values, so unset theme settings fall through to BS4's defaults.
        var options = $.extend({}, $.fn.tooltip.Constructor.Default, tooltipDefaults, $element.data());
        $element.tooltip(options);
      }
    },
    detach: function (context) {
      if (!$.fn.tooltip) {
        return;
      }
      // Destroy all tooltips. Bootstrap 4 renamed BS3's 'destroy' method to
      // 'dispose' ('destroy' throws TypeError via BS4's _jQueryInterface).
      // BS4's tooltip interface whitelists /dispose|hide/ for uninitialized
      // elements, so this is safe to call on any matched element.
      $(context).find('[data-toggle="tooltip"]').tooltip('dispose');
    }
  };

})(window.jQuery, window.Drupal, window.Drupal.bootstrap_lite);
