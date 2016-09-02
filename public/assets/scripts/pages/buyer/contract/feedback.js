/**
 * buyer/contract/feedback.js
 *
 * author: So Gwaang
 * 
 */

define(['jquery', 'jqueryui', 'jqueryrating', 'jqueryratingpack','jquerymetadata', 'jqueryvalidation', 'jquerypulsate'], function () {

  var fn = {
   	$objPulsate: null,

   	// validation using icons
	 	handleValidation: function() {
	    var $form = $('#feedback-form');
      var validator = $form.validate();
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