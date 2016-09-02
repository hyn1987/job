/**
 * view.js - Admin / Work Diary
 */

define(['notify', 'moment', 'datepicker', 'cookie'], function (nt, moment) {

	var fn = {

		nav: {
			init: function() {
				// Timezone
				$("select.wtimezone").change(function() {
					var tz = $(this).val();
					var queries = getQuery(location.href);
					var wdate = queries["wdate"] || '';
					location.href = location.protocol + "//" + location.hostname + location.pathname + "?wdate=" + wdate + "&tz=" + tz;
				});

				// Date picker
				$(".date-picker").datepicker({
					orientation: "left",
					autoclose: true

				}).on("changeDate", function(e) {
					var wdate = moment(e.date);
					wdate = wdate.format('YYYY-MM-DD');
					var queries = getQuery(location.href);
					var tz = $("select.wtimezone").val();
					location.href = location.protocol + "//" + location.hostname + location.pathname + "?wdate=" + wdate + "&tz=" + tz;
					
				});

				// grid | list
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
					if ( !comment ) {
						comment = "No memo";
					}
					var sid = $btn.data("id"); // screenshot ID

					$("h4.modal-title", fn.slot.$modal).html(comment);

					var $a = $("a.link-full", $slot);
					var fullSrc = $a.attr("href");
					var thumbSrc = $a.children("img").attr("src");
					var tz = $("select.wtimezone").val();

					$("a.link-full", fn.slot.$modal).attr("href", fullSrc).children('img.ss-img').attr("src", thumbSrc);

					$(".info-table", fn.slot.$modal).html('');

					// Load activity info
					$.post("/admin/workdiary/ajax", {
						cmd: "loadSlot",
						sid: sid,
						tz: tz
					}, function(json) {
						console.log(json);
						if ( !json.success ) {
							nt.error("Error occurred while loading activity info.");
							return false;
						}

						$(".info-table", fn.slot.$modal).html(json.html);

					}).fail(function(xhr) {
						nt.error("Failed to load activity info.");
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