/**
 * @file
 * Bootstrap Lite Popovers.
 */

var Drupal = Drupal || {};

(function ($, Drupal, BootstrapLite) {
  "use strict";

  // Popover defaults, computed from the theme settings exposed on
  // drupalSettings (BootstrapLite.settings) rather than from the Popover
  // plugin's constructor. Bootstrap 4 renamed BS3's assignable
  // `Constructor.DEFAULTS` to a getter-only `Constructor.Default` that carries
  // none of Drupal's invented `enabled`/`triggerAutoclose` keys, and
  // drupalSettings is parsed independently of Bootstrap's own load timing, so
  // this is the reliable source for the Drupal-specific popover options.
  // Bootstrap 4's Popover (like Tooltip) only understands a single
  // placement token (auto|top|bottom|left|right, see its AttachmentMap) and
  // does `placement.toUpperCase()` unguarded against undefined. BS3 allowed
  // compound values like "auto left" ("prefer left, auto-flip if no room"),
  // which have no AttachmentMap entry; feeding one through crashes
  // dynamically inside popper.min.js (`e.indexOf` on undefined) the first
  // time the popover is actually shown. Collapse a BS3 "auto <direction>"
  // value to its direction token, whitelist the result, and OMIT the key
  // entirely (not fall back to a guess) if it's still not valid, so
  // Constructor.Default's 'right' wins instead.
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

  var popoverSettings = BootstrapLite.settings || {};
  var popoverDefaults = {
    animation: !!popoverSettings.popover_animation,
    enabled: popoverSettings.popover_enabled,
    html: !!popoverSettings.popover_html,
    placement: sanitizePlacement(popoverSettings.popover_placement),
    selector: popoverSettings.popover_selector,
    trigger: popoverSettings.popover_trigger,
    triggerAutoclose: !!popoverSettings.popover_trigger_autoclose,
    title: popoverSettings.popover_title,
    content: popoverSettings.popover_content,
    delay: sanitizeDelay(popoverSettings.popover_delay),
    container: popoverSettings.popover_container
  };

  /**
   * Bootstrap Lite Popovers.
   *
   * @todo This should really be properly delegated if selector option is set.
   */
  Drupal.behaviors.bootstrapLitePopovers = {
    attach: function (context) {
      // Immediately return if popovers are not available or disabled. `enabled`
      // is a Drupal-only option and lives on popoverDefaults, not on Bootstrap
      // 4's native Constructor.Default.
      if (!$.fn.popover || !popoverDefaults.enabled) {
        return;
      }

      // Popover autoclose.
      if (popoverDefaults.triggerAutoclose) {
        var $currentPopover = null;
        $(document)
          .on('show.bs.popover', '[data-toggle=popover]', function () {
            var $trigger = $(this);
            var popover = $trigger.data('bs.popover');

            // Only keep track of clicked triggers that we're manually
            // handling. Bootstrap 4 stores the resolved options on the
            // instance as `.config`, not BS3's `.options`.
            if (popover.config.originalTrigger === 'click') {
              if ($currentPopover && !$currentPopover.is($trigger)) {
                $currentPopover.popover('hide');
              }
              $currentPopover = $trigger;
            }
          })
          .on('click', function (e) {
            var $target = $(e.target);
            var popover = $target.is('[data-toggle=popover]') && $target.data('bs.popover');
            if ($currentPopover && !$target.is('[data-toggle=popover]') && !$target.closest('.popover.in')[0]) {
              $currentPopover.popover('hide');
              $currentPopover = null;
            }
          })
        ;
      }

      var elements = $(context).find('[data-toggle=popover]').toArray();
      for (var i = 0; i < elements.length; i++) {
        var $element = $(elements[i]);
        // Merge Bootstrap 4's real native defaults (template, offset, etc.)
        // first, then the theme's popoverDefaults (enabled/placement/trigger/
        // etc.), then the per-element data attributes.
        var options = $.extend({}, $.fn.popover.Constructor.Default, popoverDefaults, $element.data());

        // Store the original trigger.
        options.originalTrigger = options.trigger;

        // If the trigger is "click", then we'll handle it manually here.
        if (options.trigger === 'click') {
          options.trigger = 'manual';
        }

        // Retrieve content from a target element.
        var $target = $(options.target || $element.is('a[href^="#"]') && $element.attr('href')).clone();
        if (!options.content && $target[0]) {
          $target.removeClass('visually-hidden hidden').removeAttr('aria-hidden');
          options.content = $target.wrap('<div/>').parent()[options.html ? 'html' : 'text']() || '';
        }

        // Initialize the popover.
        $element.popover(options);

        // Handle clicks manually.
        if (options.originalTrigger === 'click') {
          // To ensure the element is bound multiple times, remove any
          // previously set event handler before adding another one.
          $element
            .off('click.drupal.bootstrap_lite.popover')
            .on('click.drupal.bootstrap_lite.popover', function (e) {
              $(this).popover('toggle');
              e.preventDefault();
              e.stopPropagation();
            })
          ;
        }
      }
    },
    detach: function (context) {
      // Immediately return if popovers are not available or disabled.
      if (!$.fn.popover || !popoverDefaults.enabled) {
        return;
      }

      // Destroy all popovers. Bootstrap 4 renamed BS3's 'destroy' method to
      // 'dispose' ('destroy' throws TypeError via BS4's _jQueryInterface on an
      // initialized popover). Only dispose elements that were actually
      // initialized: BS4.0.0's popover interface whitelists /destroy|hide/
      // (not 'dispose') for uninitialized elements, so a bare
      // .popover('dispose') would needlessly instantiate them first.
      $(context).find('[data-toggle="popover"]').each(function () {
        var $element = $(this).off('click.drupal.bootstrap_lite.popover');
        if ($element.data('bs.popover')) {
          $element.popover('dispose');
        }
      });
    }
  };

})(window.jQuery, window.Drupal, window.Drupal.bootstrap_lite);
