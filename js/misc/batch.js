/**
 * @file
 * Drupal's batch API.
 */

(function (Drupal, $, once) {
  'use strict';

  /**
   * Attaches the batch behavior to progress bars.
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.batch = {
    attach: function (context, settings) {
      var batch = settings.batch;
      var progressBar;
      // The once call is wrapped in $() to allow the usual jQuery chaining.
      const $progress =  $(once('batch', '[data-drupal-progress]'));

      // Success: redirect to the summary.
      function updateCallback(progress, status, pb) {
        if (progress === '100') {
          pb.stopMonitoring();
          window.location = batch.uri + '&op=finished';
        }
      }

      function errorCallback(pb) {
        $progress.prepend($('<p class="error"></p>').html(batch.errorMessage));
        $('#wait').hide();
      }

      if ($progress.length) {
        var id = $progress.find('.progress').attr('id') || 'updateprogress';
        progressBar = new Drupal.ProgressBar(id, updateCallback, 'POST', errorCallback);
        $progress.replaceWith(progressBar.element);
        progressBar.setProgress(-1, batch.initMessage);
        progressBar.startMonitoring(batch.uri + '&op=do', 10);
      }
    }
  };

}(Drupal, jQuery, once));
