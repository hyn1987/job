/**
 * plugin.js
 */

define(['blockui'], function () {

  var fn = {

    commonIconImgPath: '/assets/images/common/icons/',

    /**
     * Block the page.
     */
    blockUI: function (options) {
      var options = $.extend(true, {}, options),
        html = '<div class="loading">' + (options.msg ? options.msg : '') + '</div>';

      $.blockUI({
          message: html,
          baseZ: options.zIndex ? options.zIndex : 1000,
          css: {
              border: '0',
              padding: '0',
              backgroundColor: 'none'
          },
          overlayCSS: {
              backgroundColor: '#000',
              opacity: 0.15,
              cursor: 'wait'
          }
      });
    },
    unblockUI: function () {
      $.unblockUI();
    },

    /////////////////////////////////////////////////////////////////////
    // Added by Ray
    // 
    
    blockOn: function (options) {
      var gif_name = 'loading.gif';
      var options = $.extend(true, {}, options);
      var html = '';
      if (options.iconOnly) {
          html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '')+'"><img src="' + fn.commonIconImgPath + gif_name + '" align=""></div>';
      } else if (options.textOnly) {
          html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '')+'"><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
      } else {    
          html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '')+'"><img src="' + fn.commonIconImgPath + gif_name + '" align=""><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
      }

      if (options.target) { // element blocking
          var el = $(options.target);
          if (el.height() <= ($(window).height())) {
              options.cenrerY = true;
          }            
          el.block({
              message: html,
              baseZ: options.zIndex ? options.zIndex : 1000,
              centerY: options.cenrerY != undefined ? options.cenrerY : false,
              css: {
                  top: '10%',
                  border: '0',
                  padding: '0',
                  backgroundColor: 'none'
              },
              overlayCSS: {
                  backgroundColor: options.overlayColor ? options.overlayColor : '#B0BCCC',
                  opacity: options.boxed ? 0.2 : 0.4, 
                  cursor: 'wait'
              }
          });
      } else { // page blocking
          $.blockUI({
              message: html,
              baseZ: options.zIndex ? options.zIndex : 1000,
              css: {
                  border: '0',
                  padding: '0',
                  backgroundColor: 'none'
              },
              overlayCSS: {
                  backgroundColor: options.overlayColor ? options.overlayColor : '#B0BCCC',
                  opacity: options.boxed ? 0.2 : 0.4,
                  cursor: 'wait'
              }
          });
      }            
    },


    blockOff: function (target) {
      if (target) {
          $(target).unblock({
              onUnblock: function () {
                  $(target).css('position', '');
                  $(target).css('zoom', '');
              }
          });
      } else {
          $.unblockUI();
      }
    },

    startPageLoading: function (message) {
      $('.page-loading').remove();
      $('body').append('<div class="page-loading"><img src="' + fn.commonIconImgPath + 'loading-spinner-grey.gif"/>&nbsp;&nbsp;<span>' + (message ? message : 'Loading...') + '</span></div>');
    },

    stopPageLoading: function () {
      $('.page-loading').remove();
    }


  };

	return fn;
});