/**
 * job/make_offer.js
 */

define(['jqueryvalidation', 'inputmask'], function () {
  function update_total_price() {
    var rate = parseFloat($('#hourly_rate').val());
    var limit= parseFloat($('#hourly_limit').val());
    var total= rate * limit;

    $('#hourly_total_price').html(total);
  }
  var fn = {
    init: function () {

      var $form = $("#form_make_offer");
      var validator = $form.validate();

      $("#hourly_rate").bind('keypress', update_total_price);
      $("#hourly_limit").bind('change', update_total_price);

      update_total_price();
    }
  };
  return fn;
});