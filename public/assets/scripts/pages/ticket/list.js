/**
 * list.js - Monitor / Overview
 */

define(['jquery', 'jqueryui', 'form'], function (nt) {

	var fn = {
		$objCreateFrm: null,
		$objSaveBtnCreate: null,
		$objCreateModal: null,


		$objReplyFrm: null,
		$objSaveBtnReply: null,
		$objReplyModal: null,
		$objReplyBtn: null,

		$objEditFrm: null,
		$objSaveBtnEdit: null,
		$objEditModal: null,
		$objEditBtn: null,

		$objCloseFrm: null,
		$objSaveBtnClose: null,
		$objCloseModal: null,
		$objCloseBtn: null,

		init: function () {
      //-------------------------------------------------
			// ticket-create modal
			//-------------------------------------------------

      //init create-modal
      fn.$objCreateModal = $('#createModal');
			fn.$objCreateFrm = fn.$objCreateModal.find('form#createForm');
			fn.$objSaveBtnCreate = fn.$objCreateModal.find('#saveBtn');

			//ticket create
			fn.$objSaveBtnCreate.on('click', function (event) {
				fn.$objCreateModal.modal('toggle');
				fn.$objCreateFrm.submit();
				return false;
			});


			//------------------------------------------------------------
			// ticket-reply modal
			//------------------------------------------------------------
      fn.$objReplyModal = $('#replyModal');
			fn.$objReplyFrm = fn.$objReplyModal.find('form#replyForm');
			fn.$objSaveBtnReply = fn.$objReplyModal.find('#saveBtn');

			//
		  fn.$objReplyBtn = $('#accordion .panel-heading a#replyBtn');

		  fn.$objReplyBtn.on('click', function (event) {
		  	fn.$objReplyFrm.find('#ticketId').val( $(this).data('ticket') ) ;
		  	return true;
		  });

		  // save-button event-handler
			fn.$objSaveBtnReply.on('click', function (event) {

				fn.$objReplyModal.modal('toggle');

				fn.$objReplyFrm.ajaxSubmit({
            success: function(json) {
              if (!json.success) {
                nt.error(json.msg);
                return false;
              }

              //show message detail result
	          	$('#collapse-' + json.ticketId + ' .panel-body').html(json.strComments);
            },

            error: function(xhr) {
              console.log(xhr);
            },

            dataType: 'json'
        });
			});


		  //------------------------------------------------------------
			// comment-edit modal
			//------------------------------------------------------------

			//initial
      fn.$objEditModal = $('#editModal');
			fn.$objEditFrm = fn.$objEditModal.find('form#editForm');
			fn.$objSaveBtnEdit = fn.$objEditModal.find('#saveBtn');


		  //event-handler
		  $('.panel.panel-default .panel-body').on('click', 'a#editBtn', function (event) {

		  	fn.$objEditFrm.find('#commentId').val( $(this).data('commentId') ) ;
		  	var strComment = $('#comment-' + $(this).data('commentId')).find('ul li span.comment').html();
		  	strComment = strComment.replace(/<br.?>/g, '');
		  	fn.$objEditFrm.find('textarea').val(strComment);
		  	return true;
		  });

		  // save-button event-handler
			fn.$objSaveBtnEdit.on('click', function (event) {

				fn.$objEditModal.modal('toggle');

				fn.$objEditFrm.ajaxSubmit({
            success: function(json) {
              if (!json.success) {
                nt.error(json.msg);
                return false;
              }

              //show message detail result
	          	$('#comment-' + json.commentId).find('ul li span.comment').html(nl2br(json.comment));
            },

            error: function(xhr) {
              console.log(xhr);
            },

            dataType: 'json'
        });
			});

			///////////////////////////////////////////////////////////////
			//
			//		ticket-close modal
			//
			///////////////////////////////////////////////////////////////

			//initial
      fn.$objCloseModal = $('#closeModal');
			fn.$objCloseFrm = fn.$objCloseModal.find('form#closeForm');
			fn.$objSaveBtnClose = fn.$objCloseModal.find('#saveBtn');

			//event-handler
		  $('.panel.panel-default .panel-heading').on('click', 'a#closeBtn', function (event) {
		  	fn.$objCloseFrm.find('#ticketId').val( $(this).data('ticketId') ) ;
		  	return true;
		  });

		  // save-button event-handler
			fn.$objSaveBtnClose.on('click', function (event) {

				fn.$objCloseModal.modal('toggle');

				fn.$objCloseFrm.ajaxSubmit({
            success: function(json) {
              if (!json.success) {
                nt.error(json.msg);
                return false;
              }

              //show message detail result
              $('div#collapse-' + json.ticketId + ' .panel-body').prepend(json.strComment);
              $('#heading-' + json.ticketId + ' span.status').html(json.ticketStatus);
            },

            error: function(xhr) {
              console.log(xhr);
            },

            dataType: 'json'
        });
			});

			///////////////////////////////////////////////////////////////////
			//
		  //			initailize the accordion...
		  //
		  ///////////////////////////////////////////////////////////////////
		  $objFirstPanel = $('#accordion .panel.panel-default:first');
		  
		  $objFirstPanel.find('.panel-collapse.collapse').addClass('in');
		  
		  $objFirstPanel.find('a.toggle-section span').removeClass('glyphicon-menu-down').addClass('glyphicon-menu-up');

		  //accordion up-down event
		  $arrToggleSection = $('#accordion').find('a.toggle-section');
		  $('#accordion').on('hidden.bs.collapse', function (event) {
		  	var $panel = $(event.target).closest(".panel");
		  	$panel.find('a.toggle-section span').removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');
			});

			$('#accordion').on('shown.bs.collapse', function (event) {
			  var $panel = $(event.target).closest(".panel");
		  	$panel.find('a.toggle-section span').removeClass('glyphicon-menu-down').addClass('glyphicon-menu-up');
			});
		},
	};

	return fn;
});