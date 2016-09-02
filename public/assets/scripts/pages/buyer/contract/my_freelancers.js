/**
 * job/all_contracts.js
 */

define(['jqueryrating', 'jqueryratingpack', 'jquerymetadata', 'select2'], function () {
  var fn = {
    init: function () {
      $('.project-selection').select2();
      
      //initialize the star-rating control
      $('input.rate').rating();
      $('input').rating('readOnly',true);

      $('.project-selection').on('change', function() {
        $('#form_my_freelancers').submit();
      });
    }
  };

  return fn;
});