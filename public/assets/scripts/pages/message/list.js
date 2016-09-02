/**
 * list.js - Monitor / Overview
 */

define(['jquery', 'jqueryui'], function () {	

	var fn = {
		$searchFrm: null,
		$sendFrm: null,
		$listGroup : null,
		$sendBtn : null,
    $sortSel : null,

		//get message list by thread-id
		getMessageList: function ($callType, $threadId) {
			var data = {};

      if ( $callType == 'detail' ) {
      	//set data
	      data.threadId = $threadId;
	      data.callType = $callType;
	      data.dataType = 'json';
	      
      	// Make ajax call
	      $.post('/message/list', data, function (json) { 
	          
            $('#messageSummary').html(json.strMessageSummary);
            $('#groupMessageList').html(json.strGroupMessageList);

	          //show message detail result
            if (json.strMessageSummary == '') {              
              $('#sendMessageForm').hide();
            } else {
              //collapse the last message-item
              $('#accordion').find(':last-child .panel-collapse.collapse').addClass('in');

              //set the thread-id to 'send-form'
              fn.$sendFrm.data('thread', json.threadId);
              $('#sendMessageForm').show();
            }
	          
	      });
      } else if ( $callType == 'master' ) {
      	
      	//set data
	      data.callType = $callType;
	      data.searchTitle = $('#search_title').val();
        data.sortSel = $('#sortSel').val();
	      
      	// Make ajax call
	      $.post('/message/list', data, function (json) { 

	          $('div.list-group').html(json.strHtmlMessageThreadList); 
            $('#messageSummary').html(json.strMessageSummary);
            $('#groupMessageList').html(json.strGroupMessageList);

	          //show message thread list and message list
            if (json.strMessageSummary == '') {
              $('#sendMessageForm').hide();
            } else {
              $('#sendMessageForm').show();
              //set the thread-id to 'send-form'
              fn.$sendFrm.data('thread', json.threadId);

              //set active
              $('.list-group a:first-child').addClass('active2');

              //collapse the last message-item
              $('#accordion').find(':last-child .panel-collapse.collapse').addClass('in');
            }
	          
				}); 
			}
    },
		
    sendMessage: function () {

      $('.alert.alert-danger').remove();
			var data={};
    	data.callType = 'save';
    	data.threadId = fn.$sendFrm.data('thread');
    	data.message = $('#message_content').val();

    	$.post('/message/list', data, function (json) { 
        fn.getMessageList('detail', json.threadId);
        $('#message_content').val('');
			}).fail( function (xhr, html) {
        if (xhr.status == 422) {
          $errorMsg = '<div class="alert alert-danger"><ul>';

          for (var i=0; i<xhr.responseJSON.message.length; i++) {
            $errorMsg = $errorMsg + '<li>' + xhr.responseJSON.message[i] + '</li>'
          }

          $errorMsg = $errorMsg + '</ul></div>';

          $('#message_content').parent().after($errorMsg);
        }
      }); 
		},

    readMessage: function ($messageId, $objTag) {
      var data = {};

      data.messageId = $messageId;
      data.callType = 'read_message';
      data.dataType = 'json';

      // Make ajax call
      $.post('/message/list', data, function (json) { 
        
        $objTag.removeClass('unread');
        $objMsgNotification = $('.top-menu .message .msg-notification');
        $cnt = parseInt($objMsgNotification.html());
        if ( $cnt > 1) {
          $objMsgNotification.html(parseInt($objMsgNotification.html()) - 1);
        } else {
          $objMsgNotification.remove();
        }
      });
    },

		init: function () {
      //initalize the variables
      fn.$listGroup = $("div.list-group");
      fn.$searchFrm = $('#search_form');
      fn.$sendFrm = $('#send_form');
      fn.$sendBtn = $('#send_btn');
      fn.$sortSel = $('#sortSel');
      
      //set active
      //$('.list-group a:first-child').addClass('active2');

      //collapse the last message-item
      $('#accordion').find(':last-child .panel-collapse.collapse').addClass('in');

      //define event-handler
      fn.$listGroup.on('click', 'a.list-group-item', function () {
      	
      	//call the function.
      	fn.getMessageList('detail', $(this).data('thread'));

      	//set active
      	$(this).parent().find('a.list-group-item').removeClass('active2');
      	$(this).addClass('active2');

      	//set the thread-id to 'send-form'
      	fn.$sendFrm.data('thread', $(this).data('thread'));

        $("#message_content").val('');

        return false;
      });

      //define 'Enter' key-handler
      fn.$searchFrm.on('submit', function(event){
      	fn.getMessageList('master');   
        return false;
      });

      //send-btn click event-handler
      fn.$sendBtn.on('click', function (event) {      	
      	fn.sendMessage();
      	return false;
      }); 
      
      //sort-combo event-handler
      fn.$sortSel.on('change', function () {
        fn.getMessageList('master');  
      });

      //handle the unread-message
      $('#groupMessageList').on('click', '.message-row.unread', function () {
        fn.readMessage($(this).data('messageId'), $(this));
      });
		}
	};

	return fn;
});