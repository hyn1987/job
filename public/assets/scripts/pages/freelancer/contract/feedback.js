/**
 * freelancer/contract/feedback.js
 *
 * author: So Gwang
 */

define(['jquery', 'jqueryui', 'jqueryrating', 'jqueryratingpack','jquerymetadata', 'jqueryvalidation', 'jquerypulsate'], function () {

  var fn = {
   	$objPulsate: null,

   	// validation using icons
	 	handleValidation: function() {

	    var form = $('#feedback-form');
	    var error = $('.alert-danger', form);
	    var success = $('.alert-success', form);

	    form.validate({
	      errorElement: 'span', //default input error message container
	      errorClass: 'help-block help-block-error', // default input error message class
	      focusInvalid: false, // do not focus the last invalid input
	      ignore: "",  // validate all fields including form hidden input
	      rules: {
	        rate: {
	          required: true
	        },
	      },

	      invalidHandler: function (event, validator) { //display error alert on form submit
	        error.show();                
	      },

	      errorPlacement: function (error, element) { // render error placement for each input type 
	      },

	      highlight: function (element) { // hightlight error inputs
	      	// set error class to the control group 
	        fn.$objPulsate = $('#pulsate-regular').pulsate({
		        color: "#bf1c56"
		      }); 
		      
	      },

	      unhighlight: function (element) { 
	      // revert the change done by hightlight     
	      },

	      success: function (label, element) {
	      },

	      submitHandler: function (form) {
	        if ( applyAllValues() ){
	          form.submit();
	        }        
	      }
	    });
	  },

    init: function () {
      //initialize the star-rating control.
      $('input.rate').rating({
      	//cancel: 'Cancel', 
      	//cancelValue: '0',
      	//readOnly: false,
			  callback: function(value, link){
			  	if ( value > 0 ) {
			  		$('#pulsate-regular').pulsate('destroy'); 
			  		$('#pulsate-regular').removeAttr('style');
			  		$('#pulsate-regular').css({'padding':'5px', 'margin':'0 5px'});
			  	}			    
			  }
			});    

      fn.handleValidation();
    },     
  }

  return fn;
});



  
