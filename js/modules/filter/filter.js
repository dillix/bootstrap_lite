/**
 * @file
 * Attaches behavior for the Filter module.
 */

(function (Drupal, $, once) {
  'use strict';

  function updateFilterHelpLink () {
    var $link = $(this).parents('.filter-wrapper').find('.filter-help > a');
    var originalLink = $link.data('originalLink');
    if (!originalLink) {
      originalLink = $link.attr('href');
      $link.data('originalLink', originalLink);
    }
    $link.attr('href', originalLink + '/' + $(this).find(':selected').val());
  }

  $(document).on('change', '.filter-wrapper select.filter-list', updateFilterHelpLink);

  /**
   * Displays the guidelines of the selected text format automatically.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches behavior for updating filter guidelines.
   */
  Drupal.behaviors.filterGuidelines = {
    attach: function (context) {
      // The once call is wrapped in $() to allow the usual jQuery chaining.
      const $elements =  $(once('filter-list', '.filter-wrapper select.filter-list', context));
      $elements.each(updateFilterHelpLink);
      console.log('new');
    }
  };

}(Drupal, jQuery, once));
