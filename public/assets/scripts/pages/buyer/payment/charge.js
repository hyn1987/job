/**
 * payment/charge.js
 */

define(['bootbox', 'jqueryvalidation'], function (bootbox) {
  var fn = {
    init: function () {
      fn.handleValidation();

      $('.charge-submit').on('click', function() {
        $('#form_payment_charge').submit();
      });
    },

    handleValidation : function() {

      var $form = $("#form_payment_charge");
      $form.validate({
        submitHandler: function (form) {
          var amount = $('#charge_amount').val();
          var _msg = "<div class='buyer-charging-message'>" + t(trans.charge, {'amount':currencyFormat(amount)}) + "</div>";
          bootbox.dialog({
              title: '',
              message: _msg,
              buttons: {
                  ok: {
                      label: trans.btn_ok,
                      className: 'btn-primary',
                      callback: function() {
                          form.submit(); 
                      }
                  },
                  cancel: {
                      label: trans.btn_cancel,
                      className: 'btn-default',
                      callback: function() {

                      }
                  },
              },
          });
        }
      });
    }
  };

  return fn;
});

function currencyFormat(val){
  val = val.replace(",", "", "gi");
  val = Math.round(val * 100) / 100;
  if ( parseInt(val) == val ){
    return val + '.00';
  }else if ( parseInt(val*10) == val * 10){
    return val + '0';
  }else{
    return val;
  }
}