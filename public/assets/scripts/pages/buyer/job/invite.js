/**
 * job/invite.js
 */

define(['jqueryvalidation'], function () {
  function selectProject() {
    var _url = "/job/invite/job-info";

    var job  = $(this).val();
    var contractor = $(this).data('contractor');

    $.ajax({
      url:   _url,
      type:   'POST',
      data:{'job': job, 
            'contractor' : contractor},
      beforeSend: function(jqXHR, settings) {
        $('.job-info').animate({opacity: 0}, 100);
      },
      error: function() {},
      success: function(json) {
        if (json.status == 'success') {
          $('.job-info').html(json.job_info).animate({opacity: 1}, 500);
          if (json.disable_submit) {
            $('.invite-btn').addClass('disable-submit');
          } else {
            $('.invite-btn').removeClass('disable-submit');
          }
        } else {

        }        
      },   // END OF SUCESS FUNCTION
      complete: function (jqXHR, textStatus) {

      }
    });
  }

  var fn = {
    init: function () {
      $('#job').bind('change', selectProject);
      
      var $form = $("#form_invite");
      var validator = $form.validate();
    }
  };
  return fn;
});