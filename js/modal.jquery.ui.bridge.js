/**
 * @file
 * Bootstrap Lite dialog bridge.
 *
 * Provides a `$.fn.dialog` implementation, backed by a native Bootstrap 4
 * modal, so Drupal's dialog API renders ajax dialogs as Bootstrap modals.
 *
 * Drupal core drives the lifecycle: core/misc/dialog/dialog.js's
 * `Drupal.dialog(el, options).showModal()` calls `$element.dialog(settings)`
 * (init) and `$element.dialog('close')`, and dispatches the native
 * `dialog:beforecreate` / `dialog:aftercreate` / `dialog:beforeclose` /
 * `dialog:afterclose` CustomEvents itself. This bridge therefore only needs to
 * implement the `$.fn.dialog` method surface core calls — it does NOT listen
 * for or relay any dialog:* events. It replaces the jQuery UI dialog widget
 * (which LibraryInfo::alter() strips from core/drupal.dialog), and never uses
 * jQuery UI's draggable/resizable (removed with the jquery_ui dependency).
 */
(function ($, Drupal, BootstrapLite, Attributes, drupalSettings) {
  'use strict';

  BootstrapLite.once('modal.jquery.ui.bridge', function (settings) {
    var dir = document.documentElement.getAttribute('dir');
    var rtl = dir ? dir.toLowerCase() === 'rtl' : false;

    /**
     * (Re)renders the dialog buttons into the modal footer.
     */
    function renderButtons($el, buttons) {
      var $footer = $el.find('.modal-footer');
      // The modal template only emits a footer when it has content, so create
      // one on demand (after the body) the first time buttons are rendered.
      if (!$footer.length) {
        $footer = $(Drupal.theme('bootstrapLiteModalFooter', {}, true));
        $el.find('.modal-body').after($footer);
      }
      $footer.find('.modal-buttons').remove();
      if (buttons && buttons.length) {
        var $buttons = $('<div class="modal-buttons"/>').appendTo($footer);
        for (var i = 0, l = buttons.length; i < l; i++) {
          var button = buttons[i];
          var $button = $(Drupal.theme('bootstrapLiteModalDialogButton', button));
          if (typeof button.click === 'function') {
            $button.on('click', button.click);
          }
          $buttons.append($button);
        }
        $footer.show();
      }
      else {
        $footer.hide();
      }
    }

    /**
     * Returns a per-element instance shim.
     *
     * Core's dialog.ajax.js reaches for `dialog('instance')._focusedElement`
     * and `dialog('instance')._focusTabbable()` when a form inside a dialog is
     * rebuilt (e.g. after a validation error). Provide just enough for that.
     */
    function getInstance($el) {
      var instance = $el.data('bsDialog.instance');
      if (!instance) {
        instance = {
          _focusedElement: null,
          _focusTabbable: function () {
            var $focusable = $el
              .find('a[href], area[href], button:not([disabled]), input:not([disabled]):not([type=hidden]), select:not([disabled]), textarea:not([disabled]), iframe, [tabindex]:not([tabindex="-1"])')
              .filter(':visible');
            if (this._focusedElement && $.contains($el[0], this._focusedElement)) {
              this._focusedElement.focus();
            }
            else if ($focusable.length) {
              $focusable[0].focus();
            }
            else {
              $el.attr('tabindex', -1)[0].focus();
            }
          }
        };
        $el.data('bsDialog.instance', instance);
      }
      return instance;
    }

    /**
     * Runs Drupal's dialog-close teardown exactly once per open.
     *
     * jQuery UI invokes the dialog's `close` option callback whenever the
     * dialog closes by any means; core relies on that callback (which it wraps
     * in dialog.ajax.js) to detach behaviors and remove the element. Bootstrap
     * fires `hidden.bs.modal` for programmatic and user-initiated closes alike,
     * so relay it to the stored `close` callback. A guard flag prevents the
     * re-entrant close (the callback calls Drupal.dialog(el).close() again,
     * which re-invokes $.fn.dialog('close') -> modal('hide'), a no-op once the
     * modal is already hidden).
     */
    function closeTeardown($el) {
      if ($el.data('bsDialog.tornDown')) {
        return;
      }
      $el.data('bsDialog.tornDown', true);
      var options = $el.data('bsDialog.options') || {};
      if (typeof options.close === 'function') {
        options.close({ target: $el[0] });
      }
    }

    /**
     * Handler for $.fn.dialog('option', ...).
     *
     * Supports the getter form dialog('option', name) and the setter forms
     * dialog('option', name, value) / dialog('option', {..}). Buttons and title
     * are re-applied to the live modal; core calls the buttons setter on every
     * dialogButtonsChange (in-dialog form rebuild).
     */
    function handleOption($el, args) {
      var options = $el.data('bsDialog.options') || {};

      // Getter.
      if (args.length === 1 && typeof args[0] === 'string') {
        return options[args[0]];
      }

      // Setter(s).
      var updates = {};
      if (args.length >= 2 && typeof args[0] === 'string') {
        updates[args[0]] = args[1];
      }
      else if (args.length && typeof args[0] === 'object' && args[0]) {
        updates = args[0];
      }
      $.extend(options, updates);
      $el.data('bsDialog.options', options);

      if ('buttons' in updates) {
        renderButtons($el, options.buttons || []);
      }
      if ('title' in updates) {
        $el.find('.modal-title').text(options.title == null ? '' : options.title);
      }
      return $el;
    }

    /**
     * Initializes (and shows) a Bootstrap modal for a dialog element.
     */
    function initDialog($el, options) {
      // Build a complete modal skeleton, then move the existing dialog content
      // (the ajax-inserted markup, with its event bindings intact) into the
      // modal body. Setting $el's own attributes to the modal wrapper's makes
      // $el itself the .modal element.
      var $modal = $(Drupal.theme('bootstrapLiteModal', {
        attributes: Attributes.create($el[0]).remove('style'),
        title: options.title
      }));
      var $existing = $el.contents();
      $el.removeData('bs.modal');
      $el.attr(Attributes.create($modal).toPlainObject());
      $el.append($modal.html());
      $el.find('.modal-body').append($existing);

      // Optional width -> modal-dialog max-width.
      if (options.width) {
        var width = parseInt(options.width, 10);
        if (!isNaN(width)) {
          $el.find('.modal-dialog').css('max-width', width + 'px');
        }
      }
      // Optional dialogClass -> extra classes on the modal.
      if (options.dialogClass) {
        $el.addClass(options.dialogClass);
      }

      // Persist options for later 'option' calls and close teardown.
      $el.data('bsDialog.options', options);
      $el.data('bsDialog.tornDown', false);

      // Render any initial buttons.
      renderButtons($el, options.buttons || []);

      // Relay Bootstrap's close to Drupal's dialog-close teardown (once).
      $el
        .off('hidden.bs.modal.bsDialog')
        .on('hidden.bs.modal.bsDialog', function () {
          closeTeardown($el);
        });

      // Map dialog options to Bootstrap modal options.
      var modalOptions = {
        backdrop: options.modal ? true : false,
        keyboard: options.closeOnEscape !== false,
        show: options.autoOpen !== false
      };
      // A non-dismissable modal keeps a static backdrop.
      if (options.closeOnEscape === false && options.modal) {
        modalOptions.backdrop = 'static';
      }

      // Initialize (and, per modalOptions.show, display) the real BS4 modal.
      $el.modal(modalOptions);
    }

    /**
     * $.fn.dialog: a Bootstrap-modal-backed replacement for jQuery UI's dialog.
     */
    BootstrapLite.createPlugin('dialog', function () {
      var args = Array.prototype.slice.call(arguments);
      var first = args[0];

      // Method form: $el.dialog('close' | 'option' | 'instance' | ...).
      if (typeof first === 'string') {
        var method = first;
        var methodArgs = args.slice(1);
        var ret;
        this.each(function () {
          var $el = $(this);
          switch (method) {
            case 'open':
              $el.modal('show');
              break;

            case 'close':
              if ($el.data('bs.modal')) {
                $el.modal('hide');
              }
              else {
                // Never shown; run teardown directly.
                closeTeardown($el);
              }
              break;

            case 'destroy':
              if ($el.data('bs.modal')) {
                $el.modal('hide');
              }
              break;

            case 'option':
              ret = handleOption($el, methodArgs);
              break;

            case 'instance':
              ret = getInstance($el);
              break;

            case 'isOpen':
              var data = $el.data('bs.modal');
              ret = !!(data && data._isShown);
              break;

            case 'widget':
              ret = $el;
              break;

            case 'moveToTop':
              // No stacking management needed for Bootstrap modals.
              break;

            default:
              BootstrapLite.unsupported('method', method, methodArgs);
          }
        });
        return ret !== void 0 ? ret : this;
      }

      // Initialization form: $el.dialog(options).
      var options = first || {};
      return this.each(function () {
        initDialog($(this), options);
      });
    });

    /**
     * Renders a jQuery UI Dialog compatible button element.
     *
     * @param {Object} button
     *   The button object passed in the dialog options.
     *
     * @return {String}
     *   The modal dialog button markup.
     *
     * @see http://api.jqueryui.com/dialog/#option-buttons
     * @see http://api.jqueryui.com/button/
     */
    $.extend(Drupal.theme, /** @lend Drupal.theme */ {
      bootstrapLiteModalDialogButton: function (button) {
        var attributes = Attributes.create();

        var icon = '';
        var iconPosition = button.iconPosition || 'beginning';
        iconPosition = (iconPosition === 'end' && !rtl) || (iconPosition === 'beginning' && rtl) ? 'after' : 'before';
        if (button.icon) {
          var iconAttributes = Attributes.create()
            .addClass(['ui-icon', button.icon])
            .set('aria-hidden', 'true');
          icon = '<span' + iconAttributes + '></span>';
        }

        // Value (label).
        var value = button.text;
        attributes.set('value', iconPosition === 'before' ? icon + value : value + icon);

        // Handle disabled.
        attributes[button.disabled ? 'set' : 'remove']('disabled', 'disabled');

        // Keep the descriptor's original classes (harmless in Bootstrap 4, and
        // useful as selectors — core's dialog.ajax.js finds the cancel button
        // via .dialog-cancel, which arrives in these classes).
        var classString = '';
        if (button.classes) {
          var classValues = Object.values(button.classes);
          attributes.addClass(classValues);
          classString += ' ' + classValues.join(' ');
        }
        if (button['class']) {
          attributes.addClass(button['class']);
          classString += ' ' + button['class'];
        }

        // Map to a Bootstrap 4 button context so the footer button is styled in
        // both scenarios: form-derived descriptors (bootstrap_lite's own
        // button theming already renders these with a 'btn-<context>' class,
        // not Drupal's semantic 'button--primary') and option-defined
        // descriptors (core confirm dialogs / contrib, which may carry a
        // primary flag or the semantic 'button--primary' class instead).
        // Drupal.theme.button turns 'context' into btn + btn-<context>;
        // setting it here avoids its BS3-era btn-default fallback, which is
        // unstyled in Bootstrap 4.
        //
        // If the descriptor's own classes already carry one or more
        // 'btn-<context>' classes (kept above, harmless-by-default), the
        // first one is authoritative -- it's what the theme actually
        // rendered -- so strip all of them and re-emit only the first via
        // 'context', keeping exactly one source of truth for the btn-*
        // class. Only derive a context from scratch when none is present
        // (e.g. a cancel link or a core-confirm-dialog descriptor using the
        // semantic 'button--primary' class).
        if (!attributes.get('context')) {
          var btnContextRe = /(?:^|\s)btn-(primary|secondary|success|info|warning|danger|light|dark|link)(?=\s|$)/g;
          var foundContexts = [];
          var contextMatch;
          while ((contextMatch = btnContextRe.exec(classString))) {
            foundContexts.push(contextMatch[1]);
          }

          var context;
          if (foundContexts.length) {
            context = foundContexts[0];
            foundContexts.forEach(function (foundContext) {
              attributes.removeClass('btn-' + foundContext);
            });
          }
          else {
            var isPrimary = button.primary === true || /(^|\s)button--primary(\s|$)/.test(classString);
            context = isPrimary ? 'primary' : 'secondary';
          }
          attributes.set('context', context);
        }

        // Pass through data-* attributes from the descriptor (notably
        // data-once, which core's dialog.ajax.js sets so it can avoid
        // rebinding button click handlers on every form rebuild).
        Object.keys(button).forEach(function (key) {
          if (key.indexOf('data-') === 0 && button[key] != null && button[key] !== '') {
            attributes.set(key, button[key]);
          }
        });

        return Drupal.theme('button', attributes);
      }
    });

  });

})(window.jQuery, window.Drupal, window.Drupal.bootstrap_lite, window.Attributes, window.drupalSettings);
