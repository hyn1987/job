/**
 * job/job_apply.js
 */

define(['jquery', 'jqueryui', 'jqueryvalidation', 'inputmask'], function () {
  var fn = {
    init: function () {
      $("#BillingRate").inputmask('decimal', {});
      $("#EarningRate").inputmask('decimal', {});
      $("#BillingRate").on('keyup', function(e){
        $("#EarningRate").val(currencyFormat(Math.round($(this).val() * 90) / 100));
      });
      $("#EarningRate").on('keyup', function(e){
        $("#BillingRate").val(currencyFormat(Math.round($(this).val() * 1000 / 9) / 100));
      });
    }
  };

  return fn;
});

function currencyFormat(val){
  if ( parseInt(val) == val ){
    return val + '.00';
  }else if ( parseInt(val*10) == val * 10){
    return val + '0';
  }else{
    return val;
  }
}