/**
 * list.js - Admin / User/list
 */

define(['notify'], function (notify) {

	var fn = {	
		
		list: {

			$wrapper: null,
			$list: null,

			init: function() {

				setTimeout(function(){if (nt2show) notify.success(nt2show);}, 0);
				setTimeout(function(){if (error2show) notify.error(error2show);}, 0);

				fn.list.$wrapper = $('.contract-list-wrap');
				fn.list.$list = $('ul.list', fn.list.wrapper);

				fn.list.$list.on('click', '.collapse-toggler', fn.list.toggleDetails);

				fn.list.initContractActions();
			},

			toggleDetails: function (event) {
		      event.preventDefault();


		      var $this = $(this),
		        $li = $($this.parents('li').get(0)),
		        $detail_body = $li.find('.item-body');

		      // $detail_body.collapse('toggle');

		      if ($this.is('.open')) {
		      	
		      	$detail_body.slideUp();
		        // $('.for-collapse', $li).css('visibility', 'visible');
		        $('.for-collapse', $li).css({'opacity':1});

		      } else {
		      	
		      	$('li.open', $li.parent()).each(function(i, o){
		      		if ($(o).find('.collapse-toggler').hasClass('open')) {
		      			$(o).find('.collapse-toggler').trigger('click');
		      		}
		      	});
		      	
		      	$detail_body.slideDown();
		        // $('.for-collapse', $li).css('visibility', 'hidden');
		        $('.for-collapse', $li).css({'opacity':0});
		      }

		      $this.toggleClass('open');
		      $li.toggleClass('open');
		    },

		    updateState: function(contract_id, action_type, success_callback, error_callback) {
		    	$.post('/admin/contract/update_status', {
	            	s: action_type,
	            	i: contract_id
	          	}, function(json) {
	            	console.log(json);
	            	if (!json.success) {
	              		notify.error(json.msg);
	              		if (error_callback) error_callback.call();
	              		return false;
	            	}

	            	notify.success(json.msg);
	            	if (success_callback) success_callback.call();

	            	var $li = $('#contract_' + json.i);
	            	var $status = $('.contract-state', $li);
	            	var $dropdown = $('.dropdown', $li);

	            	for (var i=0; i<10; i++) {
	            		$status.removeClass('contract-state-' + i);
	            		$dropdown.removeClass('action-status-' + i);
	            	}
	            	$status.addClass('contract-state-' + json.s).find('>i').removeClass().addClass(json.sc);
	            	$dropdown.addClass('action-status-' + json.s);
	          	});
		    },

		    initContractActions: function() {

		        $(".contract-list-wrap").on("click", ".status-do", function() {

		        	var $li = $(this).parents('.dd-item');
		        	var contract_id = $li.attr('data-id');
		        	var action_type = $(this).attr('ref');
		        	var title = contractStateLabels[action_type];

		        	$('.modal-title .entity', fn.modal.$modalEdit).html(title);
		        	$('.btn-save', fn.modal.$modalEdit).html(title);
		        	$('#scm_contract_id', fn.modal.$modalEdit).val(contract_id);
		        	$('#scm_contract_state', fn.modal.$modalEdit).val(action_type);

		        	fn.modal.$modalEdit.modal();
		        	return;
		          
		        });
		     }
		},

		modal: {
			$modalEdit: null,
			init: function() {
				fn.modal.$modalEdit = $("#modalConfirm4Action");	
				fn.modal.initDialog();
			},

			initDialog: function() {

		        fn.modal.$modalEdit.on("show.bs.modal", function(e) {
		          
		          notify.clear();
		          $('#contract_change_log', fn.modal.$modalEdit).val('');

		        }).on("click", ".btn-save", function() {
		        	
		        	var contract_id = $('#scm_contract_id', fn.modal.$modalEdit).val();
		        	var action_type = $('#scm_contract_state', fn.modal.$modalEdit).val();
		        	fn.list.updateState(contract_id, action_type, function(){fn.modal.$modalEdit.modal("hide");});
		        	
		        }).on("click", ".btn-cancel", function() {
		          	fn.modal.$modalEdit.modal("hide");
		        });
			},

		},

		init: function () {
			fn.modal.init();
			fn.list.init();
		}

	};

	return fn;
});