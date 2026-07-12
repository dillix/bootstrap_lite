/**
 * @file
 * Widens core's Drupal.behaviors.dialog.prepareDialogButtons() button
 * selector to also match <button type="submit">, not just
 * <input type="submit">.
 *
 * bootstrap_lite/underboot render all form submit actions as <button>, not
 * <input>, so without this, dialogOptions.buttons is always empty for any
 * form opened in a bridged modal -- the form's own inline submit button
 * still works, it's just never auto-moved into the modal footer (see
 * TECH_DEBT.md).
 *
 * Upstream drupal/bootstrap hit and fixed this exact gap by widening the
 * selector to '.form-actions :input[type=submit]' (jQuery's :input
 * pseudo-selector matches both <input> and <button>), but did it inside
 * their own forked copy of dialog.ajax.js. bootstrap_lite dropped its
 * dialog.ajax.js fork in P2 -- core's D11 dialog.ajax.js otherwise works
 * correctly with the modal bridge -- so re-forking the whole file again
 * just for one selector isn't worth reintroducing that maintenance burden.
 * Instead this uses the fork-free pattern from core issue #3089751
 * (https://www.drupal.org/project/drupal/issues/3089751): override
 * Drupal.behaviors.dialog.prepareDialogButtons() at runtime, from a small
 * theme file, with a body that is otherwise a plain copy of core's current
 * (D11) implementation -- see core/misc/dialog/dialog.ajax.js.
 *
 * Attached via libraries-extend on core/drupal.dialog.ajax so this file
 * loads after dialog.ajax.js has defined Drupal.behaviors.dialog.
 */
(function (Drupal) {

  'use strict';

  Drupal.behaviors.dialog.prepareDialogButtons = function ($dialog) {
    var buttons = [];
    // Only delta from core: also match <button type="submit">.
    var buttonSelectors = '.form-actions input[type=submit], .form-actions button[type=submit], .form-actions a.button, .form-actions a.action-link';
    var buttonElements = $dialog[0].querySelectorAll(buttonSelectors);

    buttonElements.forEach(function (button) {
      button.style.display = 'none';
      buttons.push({
        text: button.innerHTML || button.getAttribute('value'),
        class: button.getAttribute('class'),
        'data-once': button.dataset.once,
        click: function (e) {
          if (button.tagName === 'A') {
            button.click();
          }
          else {
            ['mousedown', 'mouseup', 'click'].forEach(function (event) {
              button.dispatchEvent(new MouseEvent(event));
            });
          }
          e.preventDefault();
        }
      });
    });
    return buttons;
  };

})(Drupal);
