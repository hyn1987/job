/**
 * view.js - Work Diary
 */

define(['moment', 'select2', 'datepicker', 'cookie'], function (moment) {

  var fn = {

    nav: {
      init: function() {
        $(".date-picker").datepicker({
          orientation: "left",
          autoclose: true

        }).on("changeDate", function(e) {
          var date = moment(e.date);
          date = date.format('YYYY-MM-DD');
          var queries = getQuery(location.href);
          location.href = location.protocol + "//" + location.hostname + location.pathname + "?wdate=" + date;
        });

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
      }
    },

    slot: {
      $modal: null,

      /*onImageLoad: function() {
        //$("ul.slots img.ss").loading();
        $("ul.slots img.ss").on("load", function() {
          console.log("loaded");
          $(this).unwrap();
        });
      },*/

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

          $("a.link-full", fn.slot.$modal).attr("href", fullSrc).children('img.ss-img').attr("src", thumbSrc);

          $(".info-table", fn.slot.$modal).html('');

          // Load activity info
          $.post("/workdiary/ajax", {
            cmd: "loadSlot",
            sid: sid
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

    onChooseContract: function () {
      var contract = $(this).val();
      location.href = location.protocol + "//" + location.hostname + "/workdiary/view/" + contract;
    }, 
    onChooseTimezone: function() {
      var tz = $(this).val();
      var queries = getQuery(location.href);
      var wdate = queries["wdate"] || '';
      location.href = location.protocol + "//" + location.hostname + location.pathname + "?wdate=" + wdate + "&tz=" + tz;
    },

    init: function() {
      $('#contract_selector').select2();
      $('#contract_selector').bind('change', fn.onChooseContract);
      $('select.wtimezone').bind('change', fn.onChooseTimezone);

      this.nav.init();
      this.slot.init();
    }
  };

  return fn;

});