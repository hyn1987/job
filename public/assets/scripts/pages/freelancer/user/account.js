/**
 * account.js
 */


define(['jquery', 'jqueryui', 'jqueryvalidation'], function () {

  var fn = {
    $form: null,
    $remember: null,

    init: function () {

      var $form = $('#MyAccountForm');
      var validator = $form.validate();

      // edit / cancel button 
      $(".right-action-link a.edit-action").css('display', 'block');

      $(".right-action-link a.cancel-action").bind('click', function() {
        $(this).removeClass('cancel');
        $(".right-action-link a.edit-action").css('display', 'block');
        $(".right-action-link a.cancel-action").css('display', 'none');
        $form.removeClass('editable');
      });

      $(".right-action-link a.edit-action").bind('click', function() {
        $(".right-action-link a.edit-action").css('display', 'none');
        $(".right-action-link a.cancel-action").css('display', 'block');
        $form.addClass('editable');
        
        return false;
      });

    }
  };

  return fn;
});