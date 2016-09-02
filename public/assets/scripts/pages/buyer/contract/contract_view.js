/**
 * contract/contract_view.js
 */

define(['bootbox', 'jqueryvalidation', 'jqueryrating', 'jqueryratingpack','jquerymetadata',], function (bootbox) {
  var fn = {
    slot: {
      $modal: null,

      init: function() {
        this.$modal = $("#modalPayment");

        this.$modal.on("show.bs.modal", function(e) {
          var $btn = $(e.relatedTarget);
          var _type = $btn.data("type");
          $('#payment_type').val(_type);

          $('#payment_amount').val('');
          $('#payment_note').val('');
          $('#confirm_payment').prop('checked', false);

          $title = "";
          if (_type == 'fixed') {
            $title = 'Fixed Payment';
          }
          else if (_type == 'bonus') {
            $title = 'Bonus Payment'
          }
          $("h4.modal-title", fn.slot.$modal).html($title);
        });
      }
    },

    handleValidation : function() {
      var $form = $('#form_payment');
      var validator = $form.validate();
    },

    onWeeklyLimit: function() {
      $(".edit-limit").click(function() {
        $(".section-view-limit").hide();
        $(".section-edit-limit").show();
      });

      $(".update-limit").click(function() {
        var weekly_limit = $("input[name=weekly_limit]").val();
        var cid = $("input[name=cid]").val();
        $.post("/contract/ajax", {
          cmd: "update_weekly_limit",
          cid: cid,
          weekly_limit: weekly_limit
        }, function(json) {
          if (!json.success) {
            nt.error(json.msg);
            return false;
          }

          $(".section-edit-limit").hide();
          $(".section-view-limit").show();

          $("span.limit-value").html(json.str_limit);
        });
      });

      $(".cancel-edit-limit").click(function() {
        $(".section-edit-limit").hide();
        $(".section-view-limit").show();
      });
    },

    init: function () {
      this.slot.init();
      fn.handleValidation();
      this.onWeeklyLimit();
    	//initialize the star-rating control.
      $('input.rate').rating();
      $('input').rating('readOnly',true);
    }
  };
  return fn;
});