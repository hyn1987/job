/**
 * view.js - Work Diary
 */

define(['moment', 'bootbox', 'select2', 'datepicker', 'cookie'], function (moment, bootbox) {

  var fn = {

    nav: {
      $currentDate: null,
      $timezone: null,

      // Timezone
      onChooseTimezone: function() {
        $("select.wtimezone").change(function() {
          var tz = $(this).val();
          var queries = getQuery(location.href);
          var wdate = queries["wdate"] || '';
          location.href = location.protocol + "//" + location.hostname + location.pathname + "?wdate=" + wdate + "&tz=" + tz;
        });
      },

      onChooseContract: function() {
        $('#contract_selector').select2().on('change', function () {
          location.href = location.protocol + "//" + location.hostname + "/workdiary/viewjob/" + $(this).val();
        });
      },

      onChooseDate: function() {
        $(".date-picker").datepicker({
          orientation: "left",
          autoclose: true,
          endDate: moment().format('MM/DD/YYYY')

        }).on("changeDate", function(e) {
          var date = moment(e.date);
          date = date.format('YYYY-MM-DD');
          var queries = getQuery(location.href);
          location.href = location.protocol + "//" + location.hostname + location.pathname + "?wdate=" + date;
        });
      },

      onViewMode: function() {
        $(".btn-group-viewmode .btn-mode").click(function() {
          var mode = $(this).data("mode");
          $(this).siblings(".active").removeClass("active");
          $(this).addClass("active");

          var $newPane = $(".pane-" + mode);
          var $curPane = $newPane.siblings().first();

          $curPane.fadeOut();
          $newPane.fadeIn();

          $.cookie("workdiary_mode", mode);
        });
      },

      init: function() {
        this.$currentDate = $("span.current-date");
        this.$timezone = $("select[name=wtimezone]");

        this.onChooseTimezone();
        this.onChooseContract();
        this.onChooseDate();
        this.onViewMode();

        // Check / uncheck hour
        $('input.select-hour').change(function(){
          var $hr = $(this).closest('.row-hour');
          var $chks = $hr.find('input.select-slot');
          
          $chks.prop('checked', $(this).is(':checked'));

          $("a.require-slots").toggleClass("disabled", $('input.select-slot:checked').length == 0);
        });

        // Check / uncheck slot
        $('input.select-slot').change(function(){
          var $hr = $(this).closest('.row-hour');
          var $chks = $hr.find('input.select-slot');

          var isAllChecked = ($chks.length == $chks.filter(':checked').length);
          $hr.find('input.select-hour').prop('checked', isAllChecked);

          $("a.require-slots").toggleClass("disabled", $('input.select-slot:checked').length == 0);
        });

        // Deselect all
        $('#deselectAll').click(function(){
          $('input.selectable-box[type="checkbox"]').prop('checked', false);
          $(this).add($("#delete")).addClass('disabled', true);
        });

        // Delete slots
        $('#delete').click(function() {
          var _msg = "<div class='freelancer-delete-workdiary-confirm'>"+trans.delete_screenshot+"</div>";

          bootbox.dialog({
              title: '',
              message: _msg,
              buttons: {
                  ok: {
                      label: trans.btn_ok,
                      className: 'btn-primary',
                      callback: function() {
                        var id_arr = new Array();
                        $('li span.select-box input.selectable-box:checked').each(function(){
                          id_arr.push($(this).data('id'));
                        });

                        // Contract ID
                        var cid = fn.nav.$currentDate.data("cid");

                        // Current Date
                        var currentDate = fn.nav.$currentDate.data("date");

                        $.post("/workdiary/ajaxjob", {
                          cmd: "deleteSlot",
                          sid: id_arr,
                          cid: cid,
                          date: currentDate
                        }, function(json) {
                          if ( !json.success ) {
                            return false;
                          }else{
                            location.reload();
                          }

                          $(".info-table", fn.slot.$modal).html(json.html);

                        }).fail(function(xhr) {
                          console.log(xhr);
                        });
                      }
                  },
                  cancel: {
                      label: trans.btn_cancel,
                      className: 'btn-default',
                      callback: function() {
                        var id_arr = new Array();
                        $('li span.select-box input.selectable-box:checked').each(function(){
                          id_arr.push($(this).data('id'));
                        });
                      }
                  },
              },
          });

        });

        $('#editMemo').click(function() {
          if ( $('input.select-slot:checked').length < 1 ) {
            var _msg = "<div class='freelancer-delete-workdiary-confirm'>"+trans.select_screenshot+"</div>";

            bootbox.dialog({
                title: '',
                message: _msg,
                buttons: {
                    ok: {
                        label: trans.btn_ok,
                        className: 'btn-primary',
                        callback: function() {
                        }
                    }
                },
            });

          } else {
            var $firstSlot = $('input.select-slot:checked').first().closest('li.slot');
            var existing_memo = $firstSlot.data('comment');
            var unique = true;
            
            $('input.select-slot:checked').each(function() {
              if ( existing_memo != $(this).closest('li.slot').data('comment') ){
                unique = false;
              }
            });

            var val = '';
            if ( unique ){
              val = existing_memo;
            }

            $('#EditMemoModal #newMemo').val(val).focus();
            $('#EditMemoModal').modal('show');
          }
        });

        $('#updateMemo').click(function(){
          var $_slot_array = new Array();
          $('input.select-slot:checked').each(function(){
            $_slot_array.push($(this).data('id'));
          });

          // Contract ID
          var cid = fn.nav.$currentDate.data("cid");

          // Current Date
          var currentDate = fn.nav.$currentDate.data("date");

          $.post("/workdiary/ajaxjob", {
            cmd: "editMemo",
            cid: cid,
            date: currentDate,
            sid: $_slot_array,
            memo: $('#newMemo').val()
          }, function(json) {
            if ( !json.success ) {
              return false;
            } else {
              location.reload();
            }
          }).fail(function(xhr) {
            console.log(xhr);
          });
          $('#EditMemoModal').modal('hide');
        });

        $('#insertManual').click(function(){
          
          var from_hour = $('#startHour').val();
          var to_hour = $('#endHour').val();
          var from_min = $('#startMinute').val();
          var to_min = $('#endMinute').val();

          /*
          *   Validation
          */
          /*var validation = true;

          if(from_hour > to_hour ){
            validation = false;
          } else if (from_hour == to_hour ){
            if (from_min >= to_min  ){
              validation = false;
            }
          }*/

          // Contract ID
          var cid = fn.nav.$currentDate.data("cid");
          
          // Current Date
          var currentDate = fn.nav.$currentDate.data("date");
          
          // Timezone
          var timezone = fn.nav.$timezone.val();

          // Memo
          var memo = $('#manualMemo').val()

          $.post("/workdiary/ajaxjob", {
            cmd: "addManual",
            cid: cid,
            date: currentDate,
            from_hour: from_hour,
            to_hour: to_hour,
            from_min: from_min,
            to_min: to_min,
            tz: timezone,
            memo: memo
          }, function(json) {
            if ( !json.success ) {
              return false;
            }

            location.reload();
          }).fail(function(xhr) {
            console.log(xhr);
          });

          $('#addManualModal').modal('hide');
        });
      }
    },

    slot: {
      $modal: null,

      init: function() {
               
        //this.onImageLoad();
        this.$modal = $("#modalSlot");

        this.$modal.on("show.bs.modal", function(e) {
          var $btn = $(e.relatedTarget);
          var $slot = $btn.closest("li.slot");
          var comment = $slot.data("comment");
          var sid = $btn.data("id"); // screenshot ID

          $("h4.modal-title", fn.slot.$modal).html(comment);

          var $a = $("a.link-full", $slot);
          var fullSrc = $a.attr("href");
          var thumbSrc = $a.children("img").attr("src");
          var tz = $("select.wtimezone").val();
          
          $("a.link-full", fn.slot.$modal).attr("href", fullSrc).children('img.ss-img').attr("src", thumbSrc);

          $(".info-table", fn.slot.$modal).html('');

          // Load activity info
          $.post("/workdiary/ajaxjob", {
            cmd: "loadSlot",
            sid: sid,
            tz: tz
          }, function(json) {
            console.log(json);
            if ( !json.success ) {
              return false;
            }

            $(".info-table", fn.slot.$modal).html(json.html);

          }).fail(function(xhr) {
            console.log(xhr);

          });
        });
      }
    },

    init: function() {
      this.nav.init();
      this.slot.init();
    }
  };

  return fn;

});