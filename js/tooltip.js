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
  // Bootstrap 4's Tooltip only understands a single placement token
  // (auto|top|bottom|left|right, see its AttachmentMap) and does
  // `placement.toUpperCase()` unguarded against undefined. BS3 allowed
  // compound values like "auto left" ("prefer left, auto-flip if no room"),
  // which have no AttachmentMap entry; feeding one through crashes
  // dynamically inside popper.min.js (`e.indexOf` on undefined) the first
  // time the tooltip is actually shown. Collapse a BS3 "auto <direction>"
  // value to its direction token, whitelist the result, and OMIT the key
  // entirely (not fall back to a guess) if it's still not valid, so
  // Constructor.Default's 'top' wins instead.
  var VALID_PLACEMENTS = ['auto', 'top', 'right', 'bottom', 'left'];
  function sanitizePlacement(placement) {
    if (typeof placement !== 'string') {
      return void 0;
    }
    var parts = placement.split(' ');
    var candidate = parts.length > 1 && parts[0] === 'auto' ? parts[1] : placement;
    return VALID_PLACEMENTS.indexOf(candidate) !== -1 ? candidate : void 0;
  }

  // A cleared theme setting is an empty string; parseInt('', 10) is NaN,
  // which Bootstrap would otherwise happily assign as the delay. Omit the
  // key (rather than pass NaN through) so Constructor.Default wins.
  function sanitizeDelay(delay) {
    var parsed = parseInt(delay, 10);
    return isNaN(parsed) ? void 0 : parsed;
  }

  var tooltipSettings = BootstrapLite.settings || {};
  var tooltipDefaults = {
    animation: !!tooltipSettings.tooltip_animation,
    html: !!tooltipSettings.tooltip_html,
    placement: sanitizePlacement(tooltipSettings.tooltip_placement),
    selector: tooltipSettings.tooltip_selector,
    trigger: tooltipSettings.tooltip_trigger,
    delay: sanitizeDelay(tooltipSettings.tooltip_delay),
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
