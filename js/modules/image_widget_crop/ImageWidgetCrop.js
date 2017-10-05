/**
 * @file
 * Theme hooks for the Drupal Bootstrap Lite base theme.
 */
(function ($, Drupal) {

  if (Drupal.ImageWidgetCrop && Drupal.ImageWidgetCrop.prototype && Drupal.ImageWidgetCrop.prototype.selectors && Drupal.ImageWidgetCrop.prototype.selectors.summary) {
    Drupal.ImageWidgetCrop.prototype.selectors.summary += ', > .card-header > .card-title';
  }

})(window.jQuery, window.Drupal);
