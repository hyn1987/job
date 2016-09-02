/**
 * payment/withdraw.js
 */

define(['bootbox', 'jqueryvalidation'], function (bootbox) {
  var fn = {
    init: function () {
      fn.handleValidation();
      $('.withdraw-submit').on('click', function() {
        $('#form_payment_withdraw').submit();
      });
    },

    handleValidation : function() {

      var form = $('#form_payment_withdraw');

      form.validate({
        submitHandler: function (form) {
          var amount = $('#withdraw_amount').val();
          var _msg = "<div class='freelancer-charging-message'>" + t(trans.withdraw, {'amount':currencyFormat(amount)}) + "</div>";

          bootbox.dialog({
              title: '',
              message: _msg,
              buttons: {
                  ok: {
                      label: trans.btn_ok,
                      className: 'btn-primary',
                      callback: function() {
                        $('.bootbox-confirm').modal('hide');
                        form.submit(); 
                      }
                  },
                  cancel: {
                      label: trans.btn_cancel,
                      className: 'btn-default',
                      callback: function() {
                        $('.bootbox-confirm').modal('hide');
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
  val = Math.round(val * 100) / 100;
  if ( parseInt(val) == val ){
    return val + '.00';
  }else if ( parseInt(val*10) == val * 10){
    return val + '0';
  }else{
    return val;
  }
}