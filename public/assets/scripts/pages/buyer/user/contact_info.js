/**
 * contact_info.js
 */

define(['jqueryvalidation', 'select2'], function () {

  var fn = {
    $form: null,
    $remember: null,

    init: function () {
      this.$form = $('#form_user_contact_info');
      this.$form.validate();

      $(".right-action-link a.edit-action").css('display', 'block');

      $(".right-action-link a.cancel-action").bind('click', function() {
        $(this).removeClass('cancel');
        $(".right-action-link a.edit-action").css('display', 'block');
        $(".right-action-link a.cancel-action").css('display', 'none');
        $('#form_user_contact_info').removeClass('editable');
      });

      $(".right-action-link a.edit-action").bind('click', function() {
        $(".right-action-link a.edit-action").css('display', 'none');
        $(".right-action-link a.cancel-action").css('display', 'block');
        $('#form_user_contact_info').addClass('editable');
        $('#Country').select2();
        
        return false;
      });
    }
  };

  return fn;
});