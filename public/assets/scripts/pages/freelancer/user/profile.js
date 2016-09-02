/**
 * profile.js
 *
 * @author: So Gwang
 *
 */

define(['jquery', 'jqueryui', 'jqueryrating', 'jqueryratingpack','jquerymetadata', 'jqueryvalidation', 'jcrop'], function () {

  var fn = {
    $portfolioCats: null,
    init: function () {
      fn.$portfolioCats = $("#portfolio_cats");
      //initialize the star-rating control
      $('input.rate').rating();
      $('input').rating('readOnly',true);

      // button
      $('button.btn-custom').on('click', function (event) {
        $(this).addClass('active');
        $(this).siblings().removeClass('active');
      });

      fn.$portfolioCats.on('change', function(){
        var current_cat = $(this).val();
        if (current_cat == "") {
          $(".portfolio-item").removeClass("hide");
        } else {
          $(".portfolio-item").each(function () {
            if ($(this).hasClass("category" + current_cat)) {
              $(this).removeClass("hide");
            }else {
              $(this).addClass("hide");
            }
          });  
        }
        
      });
      // init JCrop
      //$('#target').Jcrop();  
    }
  };

  return fn;
});
