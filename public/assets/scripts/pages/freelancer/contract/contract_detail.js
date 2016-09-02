/**
 * contract/contract_detail.js
 */

define(['bootbox', 'jqueryvalidation', 'jqueryrating', 'jqueryratingpack','jquerymetadata',], function (bootbox) {
  var fn = {
    slot: {
      $modal: null,

      init: function() {
        this.$modal = $("#modalPayment");

        this.$modal.on("show.bs.modal", function(e) {
          var $btn = $(e.relatedTarget);
          var _type = $btn.data("type");
          $('#payment_type').val(_type);

          $('#payment_amount').val('');
          $('#payment_note').val('');
          $('#confirm_payment').prop('checked', false);

          $title = 'Refund';
          $("h4.modal-title", fn.slot.$modal).html($title);
        });
      }
    },

    handleValidation : function() {

      var form = $('#form_payment');
      var error = $('.alert-danger', form);
      var success = $('.alert-success', form);

      form.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",  // validate all fields including form hidden input
        rules: {
          payment_amount: {
            required: true, 
            number  : true, 
          }, 
          confirm_payment: {
            required: true, 
          }
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
    }, 

    init: function () {
      this.slot.init();
      fn.handleValidation();
    	//initialize the star-rating control.
      $('input.rate').rating();
      $('input').rating('readOnly',true);
    }
  };
  return fn;
});