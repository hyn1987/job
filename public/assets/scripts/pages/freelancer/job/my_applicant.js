/**
 * job/my_applicant.js
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
      // change term action
      $('#ChangeTermModal #changeTerm').click(function(){
        $('input[name="type"]').val('T');
        $('input[name="rate"]').val($('input#BillingRate').val());
        $('form#ApplicantDetailForm').submit();
      });

      // withdraw proposal
      $('#WithdrawProposalModal #withdrawProposal').click(function(){
        $('input[name="type"]').val('W');
        $('input[name="reason"]').val($('#Reason').val());
        $('form#ApplicantDetailForm').submit();
      });

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

function currencyFormat(val){
  if ( parseInt(val) == val ){
    return val + '.00';
  }else if ( parseInt(val*10) == val * 10){
    return val + '0';
  }else{
    return val;
  }
}