/**
 * list.js - Monitor / Overview
 */

define(['jquery', 'jqueryui'], function () {	

	var fn = {
		$closeNotification: null,
    $notificationItem: null,

    //delete the clicked notification
    deleteNotification: function ($nItem) {
      var notificationId = $nItem.attr("notification-id");
      var _url = siteUrl + '/notification/delete/' + notificationId;
      // Make ajax call
      $.ajax({
        url:   _url,
        type:   'POST',
        data:{},
        beforeSend: function(jqXHR, settings) {},
        error: function() {},
        success: function(json) {
          if (json.status == 'success') {
            if ($nItem.hasClass("unread")) {
              var $header_notification = $("#header_notification_bar");
              var notification_cnt = parseInt($header_notification.find(".notfication-cnt").text()) - 1;
              $header_notification.find(".nid" + notificationId ).remove();
              $header_notification.find(".notfication-cnt").html( notification_cnt == 0? '' :  notification_cnt - 1 );
            }
            $nItem.remove();

          } else {

          }        
        },   // END OF SUCESS FUNCTION
        complete: function (jqXHR, textStatus) {
          
        }
      });
    },

    //read the clicked notification
    readNotification: function($nItem) {
      var notificationId = $nItem.attr("notification-id");
      var _url = siteUrl + '/notification/read/' + notificationId;
      // Make ajax call
      $.ajax({
        url:   _url,
        type:   'POST',
        data:{},
        beforeSend: function(jqXHR, settings) {},
        error: function() {},
        success: function(json) {
          if (json.status == 'success') {
            var $header_notification = $("#header_notification_bar");
            var notification_cnt = parseInt($header_notification.find(".notfication-cnt").text()) - 1;

            $header_notification.find(".nid" + notificationId ).remove();
            $header_notification.find(".notfication-cnt").html( notification_cnt == 0? '' :  notification_cnt - 1 );
            $nItem.removeClass("unread");
          } else {

          }        
        },   // END OF SUCESS FUNCTION
        complete: function (jqXHR, textStatus) {
          
        }
      });
    },

		init: function () {

      //Init the variables
      fn.$closeNotification = $(".notification-close");
      fn.$notificationItem = $(".notification-item");

      //define event-handler
      fn.$closeNotification.click(function () {
        fn.deleteNotification($(this).parent().parent().parent());
        return true;
      });

      fn.$notificationItem.click(function () {
        var $this = $(this);
        if ($this.hasClass("unread")) {
          fn.readNotification($this);
        } else {
          return false;
        }
        return true;
      });

		}
	};

	return fn;
});