/**
 * job/create.js
 */

define(['wjbuyer', 'bootstrapselect'], function (buyer) {
  var fn = {
    init: function () {
      initJobsSelectLinkHandler();
    }
  };

  function initJobsSelectLinkHandler() {
    $('.job-action-control').selectpicker();

    $('.job-info').on('click', ".dropdown-menu div.job-action-link", function(e) {
      if ($(this).hasClass('direct-link')) {
        window.location.href = $(this).data('url');
      }
      else if ($(this).hasClass('public-link')) {
        buyer.job_change_public(e, $(this));       
      }
      else if ($(this).hasClass('status-link')) {
        buyer.job_change_status(e, $(this));
      }

    });
  }
  return fn;
});