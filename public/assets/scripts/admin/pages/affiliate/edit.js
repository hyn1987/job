/**
 * list.js - Admin / FAQ/List
 */

define(['plugin', 'bootbox', 'growl'], function (plugin, bootbox) {

	var fn = {	
		$updateBtn: null,
    updateUrl: '',
    update: function () {
      // make ajax post call to save the changes.
      bootbox.dialog({
        title: '',
        message: trans.affiliate_update,
        buttons: {
          yes: {
            label: trans.btn_yes,
            className: 'btn-success btn-sm',
            callback: function () {
              plugin.blockUI();
              $.post(fn.updateUrl, {affiliate_id: $("#affiliate_id").val(), percent: $("#percent").val(), duration: $("#duration").val()}, function (json) {
                plugin.unblockUI();
                if (json.status == 'success') {
                  $.bootstrapGrowl(trans.affiliate_saved, {
                    type: 'default',
                    align: 'center',
                    width: 'auto',
                    delay: 3000,
                  });
                }
              });
            },
          },
          no: {
            label: trans.btn_no,
            className: 'btn-default btn-sm',
          },
        },
      });
      
    },

		init: function () {
      fn.$updateBtn = $(".btn-affiliate");
      fn.updateUrl = data.updateUrl;
			fn.$updateBtn.on('click', fn.update);
		}

	};

	return fn;
});

