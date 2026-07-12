/**
 * @file
 * Extends methods from core/misc/tabledrag.js.
 */
(function ($) {

  /**
   * Add an asterisk or other marker to the changed row.
   */
  Drupal.tableDrag.prototype.row.prototype.markChanged = function () {
    var $cell = $('td:first', this.element);
    // Find the first appropriate place to insert the marker.
    var $target = $($cell.find('.file-size').get(0) || $cell.find('.file').get(0) || $cell.find('.tabledrag-handle').get(0));
    if (!$cell.find('.tabledrag-changed').length) {
      $target.after(' ' + Drupal.theme('tableDragChangedMarker') + ' ');
    }
  };

  $.extend(Drupal.theme, /** @lends Drupal.theme */{

    /**
     * @return {string}
     */
    tableDragChangedMarker: function () {
      return Drupal.theme('bootstrapLiteIcon', 'warning-sign', {'class': ['tabledrag-changed', 'text-warning']});
    },

    /**
     * @return {string}
     */
    tableDragChangedWarning: function () {
      return '<div class="tabledrag-changed-warning alert alert-sm alert-warning messages warning">' + Drupal.theme('tableDragChangedMarker') + ' ' + Drupal.t('You have unsaved changes.') + '</div>';
    },

    /**
     * BS4/glyphicon-styled tableDrag handle.
     *
     * Core's makeDraggable() (unmodified, kept via the drop of this file's
     * former constructor/makeDraggable overrides) locates the drag-init
     * target by the "tabledrag-handle" class and passes core's own
     * dragOrientation ('drag'/'drag-y') straight through here.
     *
     * @param {string} [dragOrientation]
     *   'drag-y' for a vertical-only reorder, 'drag' (default) otherwise.
     *
     * @return {string}
     *   HTML markup for a tableDrag handle.
     */
    tableDragHandle: function (dragOrientation) {
      var title = dragOrientation === 'drag-y' ? Drupal.t('Change order') : Drupal.t('Move in any direction');
      return '<a href="#" class="tabledrag-handle" title="' + title + '" data-toggle="tooltip">' + Drupal.theme('bootstrapLiteIcon', 'move') + '</a>';
    },

    /**
     * BS4-styled weight-toggle button and its wrapper.
     *
     * Core's constructor (unmodified) locates the button by
     * data-drupal-selector="tabledrag-toggle-weight" to set
     * $toggleWeightButton and bind its click handler - that attribute must
     * stay on the actual <button>.
     *
     * @return {string}
     *   HTML markup for the weight toggle button and its container.
     */
    tableDragToggle: function () {
      var button = Drupal.theme('btn-sm', {
        'class': ['tabledrag-toggle-weight'],
        'data-drupal-selector': 'tabledrag-toggle-weight',
        title: Drupal.t('Re-order rows by numerical weight instead of dragging.'),
        'data-toggle': 'tooltip'
      });
      return '<div class="tabledrag-toggle-weight-wrapper" data-drupal-selector="tabledrag-toggle-weight-wrapper">' + button + '</div>';
    }
  });

})(jQuery);
