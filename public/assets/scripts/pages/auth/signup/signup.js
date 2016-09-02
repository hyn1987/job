 /**
    * @desc - register form validation
    * @author - Ri Chol Min
    * @modify - 12/24/2015
    */

var FormValidation = function () {
	// validation using icons
	var handleValidation = function() {
		var form = $('#RegisterForm');
		var error = $('.alert-danger', form);
		var success = $('.alert-success', form);

		form.validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: "",  // validate all fields including form hidden input
			rules: {
				first_name: {
					minlength: 1,
					required: true
				},
				last_name: {
					minlength: 1,
					required: true
				},
				email: {
					required: true,
					email: true
				},
				country: {
					required: true
				},
				username: {
					minlength: 1,
					required: true
				},
				password: {
					minlength: 8,
					required: true
				},
				password2: {
					minlength: 8,
					required: true
				},
				selectquestion: {
					required: true
				},
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
                if ( element_id == 'Password' || element_id == 'Password2' ){
                    var $_pwd1 = $('#Password');
                    var $_pwd2 = $('#Password2');
                    if ( $_pwd1.val() !== $_pwd2.val() ){
                        var icon1 = $_pwd1.parent('.input-icon').children('i');
                        var icon2 = $_pwd2.parent('.input-icon').children('i');
                        icon1.removeClass('fa-check').addClass("fa-warning");
                        icon1.attr('data-original-title', 'Password Mismatch');
                        $_pwd1.closest('.form-group').removeClass('has-success').addClass('has-error');
                        icon2.removeClass('fa-check').addClass("fa-warning");
                        icon2.attr('data-original-title', 'Password Mismatch');
                        $_pwd2.closest('.form-group').removeClass('has-success').addClass('has-error');
                        error.show();
                    }else{
                        var icon1 = $_pwd1.parent('.input-icon').children('i');
                        var icon2 = $_pwd2.parent('.input-icon').children('i');
                        icon1.removeClass("fa-warning").addClass("fa-check");
                        icon1.attr('data-original-title', 'Password Match');
                        $_pwd1.closest('.form-group').removeClass('has-error').addClass('has-success');
                        icon2.removeClass("fa-warning").addClass("fa-check");
                        icon2.attr('data-original-title', 'Password Match');
                        $_pwd2.closest('.form-group').removeClass('has-error').addClass('has-success');
                        error.hide();                        
                    }                       
                }
			},

			submitHandler: function (form) {
				var $_pwd1 = $('#Password');
				var $_pwd2 = $('#Password2');
				if ( $_pwd1.val() !== $_pwd2.val() ){
					var icon1 = $_pwd1.parent().find('.fa.tooltips');
					var icon2 = $_pwd2.parent().find('.fa.tooltips');
					icon1.removeClass('fa-check').addClass("fa-warning");
					icon2.removeClass('fa-check').addClass("fa-warning");
					icon1.val('data-original-title', 'Password Mismatch');
					icon2.val('data-original-title', 'Password Mismatch');
                    $_pwd1.closest('.form-group').removeClass('has-success').addClass('has-error');
                    $_pwd2.closest('.form-group').removeClass('has-success').addClass('has-error');
                    error.show();
				}else{
					form.submit();
				}				
			}
		});
	}
	return {
		//main function to initiate the module
		init: function () {
			handleValidation();
		}
	};
}();