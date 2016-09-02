/**
 * common.js
 *
 * This scripts serves to support the scripts on the header and sidebar etc as common.
 */

define(['jquery', 'uniform', 'jqueryvalidation'], function ($) {

  var fn = {

    initValidator: function() {

      var options = {
        ignore: '',
        errorElement: 'span',
        errorPlacement: function(error, element) {
          if (element.parent().is('.form-line-wrapper')) {
            element.parent().after(error);
          } else if (element.parent().hasClass("form-md-line-input")) {
            element.parent().append(error);
          } else {
            error.insertAfter(element);
          }
        }
      };

      if (typeof lang == 'undefined') {
        lang = 'en';
      }

	  if (lang == 'en') {
      /*
      messages: {
      	required: "This field is required.",
      	remote: "Please fix this field.",
      	email: "Please enter a valid email address.",
      	url: "Please enter a valid URL.",
      	date: "Please enter a valid date.",
      	dateISO: "Please enter a valid date ( ISO ).",
      	number: "Please enter a valid number.",
      	digits: "Please enter only digits.",
      	creditcard: "Please enter a valid credit card number.",
      	equalTo: "Please enter the same value again.",
      	maxlength: $.validator.format( "Please enter no more than {0} characters." ),
      	minlength: $.validator.format( "Please enter at least {0} characters." ),
      	rangelength: $.validator.format( "Please enter a value between {0} and {1} characters long." ),
      	range: $.validator.format( "Please enter a value between {0} and {1}." ),
      	max: $.validator.format( "Please enter a value less than or equal to {0}." ),
      	min: $.validator.format( "Please enter a value greater than or equal to {0}." )
      },
      */
	  } else if (lang == 'ch') {
        $.validator.messages = {
          required: "请输入这个条目。",
          remote: "请输入正确的值。",
          email: "请输入正确的邮址。",
          url: "请输入正确的URL。",
          date: "请输入正确的日期。",
          dateISO: "请输入正确的日期(ISO)。",
          number: "请输入正确的数字。",
          digits: "只可填写数字。",
          creditcard: "请输入正确的信用卡号。",
          equalTo: "请再输入同一个值。",
          maxlength: $.validator.format( "最大字数为{0}。" ),
          minlength: $.validator.format( "最少字数为{0}。" ),
          rangelength: $.validator.format( "字数范围为P{0}至{1}。" ),
          range: $.validator.format( "输入值范围为{0}至{1}。" ),
          max: $.validator.format( "最大数值为{0}。" ),
          min: $.validator.format( "最小数值为{0}。" )
        };
      } else if (lang == 'kp') {
        $.validator.messages = {
        	required: "이 항목은 필수항목입니다.",
        	remote: "이 항목의 값을 수정하십시오.",
        	email: "정확한 Email주소를 입력하십시오.",
        	url: "정확한 URL을 입력하십시오.",
        	date: "정확한 날자를 입력하십시오.",
        	dateISO: "정확한 ISO날자를 입력하십시오.",
        	number: "정확한 수값을 입력하십시오.",
        	digits: "수자만을 입력할수 있습니다.",
        	creditcard: "정확한 신용카드번호를 입력하십시오.",
        	equalTo: "같은 값을 다시 입력하십시오.",
        	maxlength: $.validator.format( "최대 {0}문자까지 입력할수 있습니다." ),
        	minlength: $.validator.format( "최소 {0}문자는 입력하여야 합니다." ),
        	rangelength: $.validator.format( "{0} ~ {1}문자사이의 값을 입력하십시오." ),
        	range: $.validator.format( "{0} ~ {1}사이의 값을 입력하십시오." ),
        	max: $.validator.format( "{0}보다 작거나 같은 값을 입력하십시오." ),
        	min: $.validator.format( "{0}보다 크거나 같은 값을 입력하십시오." )
        };
      }

      $.validator.setDefaults(options);
    },

    /**
     * Init scripts associated with the elements on the header.
     */
    initHeader: function () {
      $('.sysnotification').on("click", function(){
        var notificationId = parseInt($(this).attr("sysnotification-id"));
        var _url = siteUrl + '/notification/read/' + notificationId;

        $.ajax({
            url:   _url,
            type:   'POST',
            data:{},
            beforeSend: function(jqXHR, settings) {},
            error: function() {},
            success: function(json) {
              if (json.status == 'success') {
                return true;
              } else {
                return false;
              }
            },   // END OF SUCESS FUNCTION
            complete: function (jqXHR, textStatus) {

            }
          });
      });

      $('#header_notification_bar .dropdown-menu li.notification').on("click", "a", function() {
          var $this_parent = $(this).parent();
          var $unread_notify_obj = $this_parent.parent().prev().find(".notfication-cnt");
          var _url = siteUrl + '/notification/read/' + $(this).attr("notification-id");

          $.ajax({
            url:   _url,
            type:   'POST',
            data:{},
            beforeSend: function(jqXHR, settings) {},
            error: function() {},
            success: function(json) {
              if (json.status == 'success') {
                $this_parent.slideToggle("slow", function(){
                  $this_parent.remove();
                  var unread_notify_cnt = parseInt($unread_notify_obj.text()) - 1;
                  if (unread_notify_cnt == 0)
                  {
                    $unread_notify_obj.html('');
                  } else
                  {
                    $unread_notify_obj.html(unread_notify_cnt);
                  }
                  $(".notification-list-wrap").find( ".nid" + json.notification_id ).removeClass("unread");
                });
              } else {

              }
            },   // END OF SUCESS FUNCTION
            complete: function (jqXHR, textStatus) {

            }
          });

          return false;
      });

      //////////////////////////////////////////////////
      //init search by sogwang
      //////////////////////////////////////////////////
      $('.search-box2').on('click', function () {
        return true;
      });

      $('#header_search_bar a').on('click', function () {
        $('.search-box2').show();
      });

      $('#searchType').on('change', function () {
        var searchtype = $(this).val();
        if (searchtype == 'job') {
          $('#frm_header_search').attr('action', "/search/job");
        } else if (searchtype == 'user') {
          $('#frm_header_search').attr('action', "/search/user");
        }
      });
    },

    /**
     * Add the functionalities associated with scroll event.
     */
    bindScroll: function () {
      $(window).scroll(function (event) {
        var scrollTop = $(window).scrollTop();

        if (scrollTop) {
          $('body').addClass('scroll');
        } else {
          $('body').removeClass('scroll');
        }
      });

      $(window).trigger('scroll');
    },

    ajaxSetup: function() {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    },

    initHandleInput: function() {
      // Floating labels
      var handleInput = function(el) {
        if (el.val() != "") {
          el.addClass('edited');
        } else {
          el.removeClass('edited');
        }
      } 

      $('body').on('keydown', '.form-md-floating-label .form-control', function(e) { 
        handleInput($(this));
      }).on('keydown', '.form-md-floating-label .select2', function(e) { 
        handleInput($(this).prev());
      }).on('blur', '.form-md-floating-label .form-control', function(e) { 
        handleInput($(this));
      }).on('change', 'select.select2', function(e) {
        $(this).trigger('blur');
      });
    },

    /**
     * Init common scripts.
     */
    init: function () {
      this.initValidator();
      this.bindScroll();
      this.initHeader();
      this.ajaxSetup();
      this.initHandleInput();
    }
  };

	return fn;
});

function t(str, pattern, lang) {
  for (var prop in pattern) {
    if( pattern.hasOwnProperty( prop ) ) {
      str = str.replace(':'+prop, pattern[prop]);
    }
  }
  return str;
}