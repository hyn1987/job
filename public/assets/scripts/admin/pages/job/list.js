/**
 * list.js - Admin / Search Users
 */

define(['notify', 'moment', 'daterangepicker'], function (nt, moment) {

	var fn = {

    search: {

      initDateRanger: function() {
        $('#date_range').daterangepicker({
            opens: 'left',
            format: 'MM/DD/YYYY',
            ranges: {
              'Today': [moment(), moment()],
              'Last 7 days': [moment().subtract(6, 'days'), moment()],
              'Month to date': [moment().startOf('month'), moment()],
              'Year to date': [moment().startOf('year'), moment()],
              'The previous month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },            
            separator: ' to ',
            startDate: moment().subtract('days', 29),
            endDate: moment(),
            minDate: '01/01/2012',
            maxDate: moment()
          },

          function (start, end) {
            var s = start.format('MMM D, YYYY'); 
            var e = end.format('MMM D, YYYY'); 
            var str;

            if (s == e) {
              str = s;
            } else {
              str = s + " - " + e;
            }

            $('#date_range input').val(str);

            $("#frm_search").find("input[name=date_range]").val(str).end()
              .find('input[name=page]').val(1).end()
              .submit();
          }
        );   
      },

      onPagination: function() {
        $("ul.pagination").on("click", "a", function() {
          var $li = $(this).parent();
          if ($li.hasClass("disabled")) {
            return;
          }

          var page = $(this).text();

          $("#frm_search").find('input[name=page]').val(page).end().submit();

          return false;
        });
      },

      init: function() {
        this.initDateRanger();
        this.onPagination();
      }
    },

    list: {

      onSummaryMore: function() {
        $(".more-anchor").on("click", ".more", function() {
          $(this).parent().hide();
          $(this).closest(".job-inner").find(".summary-more").removeClass("hide");
        });
      },

      onJobActions: function() {
        $(".job-items").on("click", ".btn-job-action", function() {
          var _this = $(this);

          var type = _this.data("type");  // cancel | reopen
          var jid = _this.data("jid"); // Job ID

          $.post('/admin/job/ajax', {
            cmd: type,
            id: jid
          }, function(json) {
            console.log(json);
            if (!json.success) {
              nt.error(json.msg);
              return false;
            }

            _this.closest(".jpanel").removeClass("open closed").addClass(json.isOpenNew);
            nt.success(json.msg);
          });
          
        });
      },

      init: function () {
        this.onSummaryMore();
        this.onJobActions();
      }
		},

    init: function() {
      this.search.init();
      this.list.init();
    }
	};

	return fn;
});