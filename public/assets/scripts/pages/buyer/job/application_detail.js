/**
 * buyer/job/application_detail.js
 */

define(['wjbuyer', 'jqueryvalidation'], function (buyer) {

  var fn = {
    $form: null,
    $remember: null,

    init: function () {
      buyer.initApplicationLinkHandler();
      FormValidation.init();

      // Last Accordion Expanded
      $('.message-section .panel:last-child').find('.panel-collapse.collapse').addClass('in');

      if (_action == 'send_message') {
        var $last_msg = $('.message-section .panel:last-child .message-item:last-child')
        var _pos = $last_msg.offset();
        window.scrollTo(0, _pos.top-10);
        $('#message').focus();
        $last_msg.animate({opacity: 0.1}, 100).animate({opacity: 1}, 500);
      }
      else if (_action == 'message') {
        var _pos = $('#message').offset();
        window.scrollTo(0, _pos.top-70);
        $('#message').focus();
      }
    }
  };

  return fn;
});

var FormValidation = function () {
  // validation using icons
  var handleValidation = function() {

    var form = $('#form_send_message');
    var error = $('.alert-danger', form);
    var success = $('.alert-success', form);

    form.validate({
      errorElement: 'span', //default input error message container
      errorClass: 'help-block help-block-error', // default input error message class
      focusInvalid: false, // do not focus the last invalid input
      ignore: "",  // validate all fields including form hidden input
      rules: {
        message: {
          required: true
        }, 
      },

      invalidHandler: function (event, validator) { //display error alert on form submit
        error.show();                
      },

      errorPlacement: function (error, element) { // render error placement for each input type 
        var icon = $(element).parent('.input-icon').children('i');
        icon.removeClass('fa-check').addClass("fa-warning");  
        icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
      },

      highlight: function (element) { // hightlight error inputs
        $(element)
          .closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
      },

      unhighlight: function (element) { // revert the change done by hightlight
        
      },

      success: function (label, element) {
        var icon = $(element).parent('.input-icon').children('i');
        $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
        icon.removeClass("fa-warning").addClass("fa-check");
        
        var element_id = $(element).attr('id');
      },

      submitHandler: function (form) {
        form.submit();     
      }
    });
  }
  return {
    //main function to initiate the module
    init: function () {
      handleValidation();
    }
  };
}();  