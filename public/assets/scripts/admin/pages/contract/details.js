/**
 * list.js - Admin / User/list
 */

define(['notify'], function (notify) {

	var fn = {	
		
		details: {

			$wrapper: null,

			init: function() {

				fn.details.$wrapper = $($('.page-body').eq(0));

				$('.tooltips').tooltip('destroy').tooltip({
		            placement: 'left'
		        });

		        fn.details.$wrapper.on('click', '.lnk_feedbackviewr', function(e){
		        	e.preventDefault();
		        	var $fw = $($(this).parents('.feedback-block').eq(0));

		        	$('.mdfv_feedback', fn.modal.$modalViewer).html($('.feedback-content', $fw).html());
		        	$('.star.set', fn.modal.$modalViewer).width($fw.attr('rating'));
		        	if ($fw.attr('ratingFrom') == '0') {
		        		$('.feedback-type i', fn.modal.$modalViewer).removeClass('fa-arrow-left').addClass('fa-arrow-right');
		        	} else {
		        		$('.feedback-type i', fn.modal.$modalViewer).removeClass('fa-arrow-right').addClass('fa-arrow-left');
		        	}

		        	fn.modal.$modalViewer.modal();
		        });

			}
		},

		modal: {
			$modalViewer: null,
			init: function() {
				fn.modal.$modalViewer = $("#modalFeedbackViewer");	
				fn.modal.initDialog();
			},

			initDialog: function() {

		        fn.modal.$modalViewer.on("show.bs.modal", function(e) {
		          notify.clear();
		        }).on("click", ".btn-cancel", function() {
		          	fn.modal.$modalViewer.modal("hide");
		        });
			},

		},

		init: function () {
			fn.modal.init();
			fn.details.init();
		}

	};

	return fn;
});