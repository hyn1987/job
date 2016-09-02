/**
 * freeelancer.js - Sign Up Page
 */

define(['jqueryui', 'select2'], function () {

	var fn = {

		init: function () {
    	this.$form = $("#frm_register");
      this.validator = this.$form.validate();

      $("#ele_username, #ele_email").change(function() {
        var element = this;
        var element_id = $(element).attr('id');
        var element_name = $(element).attr('name');
        var element_val = $(element).val();

        if (!element_val) {
          return false;
        }

        if (element_id == 'ele_username' || element_id == 'ele_email'){
          var field = '';
          if (element_id == 'ele_username') {
            field = 'username';
          } else if (element_id == 'ele_email') {
            field = 'email';
          }

          $.ajax({
            url: "/user/signup/checkfield",
            data: {
              field: field,
              value: element_val
            }
          }).done(function(response) {
            if (response.success) {
              // hide error message
              console.log("Success");
            } else {
              // show response.msg
              // var msg = 'This username already exists.';
              // var msg = 'This email address already exists.';
              //console.log(response.msg);
              errors = {};
              errors[element_name] = response.msg;
              fn.validator.invalid[element_name] = true;
              fn.validator.showErrors(errors);
            }
          });
        }
      });

      $(".select2").select2({
        minimumResultsForSearch: 6
      });
		}
    
	};

	return fn;
});
