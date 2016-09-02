/**
 * job/edit.js
 */

define(['wjbuyer', 'jqueryui', 'select2'], function (buyer) {

  var fn = {
    init: function () {
      $('.job-category').select2();

      this.$form = $("#form_job_edit");
      this.validator = this.$form.validate();
      

      $(".job-type-section #hourly_job").click(function() {
        $('#form_job_edit').removeClass('fixed-job').addClass('hourly-job');
      });
      $(".job-type-section #fixed_job").click(function() {
        $('#form_job_edit').addClass('fixed-job').removeClass('hourly-job');
      });

      buyer.initTopLinkHandler();
    }
  };

  return fn;
});