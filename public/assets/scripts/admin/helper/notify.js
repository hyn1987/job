/**
 * notify.js
 *
 * This scripts serves to support the notification scripts.
 */

define(['jquery', 'growl'], function ($) {
	var notify = {
		timeInterval: 3000
	};

  notify.alert = function(text, type, align, delay, width) {
    if (typeof text == "undefined" || text == "") {
    	console.error("[notify.alert] Message is empty.");
      return false;
    }

    align = align || 'center';
    type = type || 'success';

    if (type == "error") {
      type = "danger";
    }

		if (text == null || typeof text == "undefined" || text == "") {
    	type = "danger";
    	text = "[2001] Empty message! Please contact the administrator.";
    }

    // if this is not wrapped in HTML tags, wrap this in <p> tag
    if (text.substr(0, 1) != "<") {
      text = '<p>' + text + '</p>';
    }

    if (delay == undefined || delay == 0) {
      var delays = {
        success: 5000,
        info: 16000,
        warning: 20000,
        danger: 30000
      };

      if (delays[type] != undefined) {
        delay = delays[type];
      } else {
        delay = 20000;
      }
    }

    var defaultWidth = 500;
    width = width || defaultWidth;

    if (window.innerWidth < width) {
      width = window.innerWidth * 0.8;
    }

    $.bootstrapGrowl(text, {
      align: align,
      type: type,
      offset: {
        from: "top",
        amount: 36
      },
      delay: delay,
      width: width
    });
  };

  /* Paul Z. - 2015.3.5 */
  notify.clear = function() {
    $.bootstrapGrowl.clear();
  };

  notify.error = function(text, align, delay, width) {
    notify.alert(text, "danger", align, delay, width);
  };

  notify.warning = function(text, align, delay, width) {
    notify.alert(text, "warning", align, delay, width);
  };

  notify.success = function(text, align, delay, width) {
    notify.alert(text, "success", align, delay, width);
  };

  notify.info = function(text, align, delay, width) {
    notify.alert(text, "info", align, delay, width);
  };

  notify.close = function() {
    $('.bootstrap-growl').alert('close');
  };

  //window.nt = notify;

	return notify;
});