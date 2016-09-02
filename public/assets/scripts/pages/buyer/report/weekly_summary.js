/**
 * report/weekly_summary.js
 */

define(['wjbuyer', 'moment', 'daterangepicker', 'bootstrapselect'], function (buyer, moment) {
  var fn = {
    filter: {
      initDateRanger: function() {
        $('#date_range').daterangepicker({
            opens: 'right',
            format: 'MM/DD/YYYY',
            ranges: {
              'This Week': [moment().startOf('isoWeek'), moment().endOf('isoWeek')],
            }, 
            separator: ' to ',
            startDate: moment(date_from),
            endDate: moment(date_to),
            //singleDatePicker: true, 
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

            $("#frm_summary_filter").submit();
          }
        ); 

        // Previous, Next Range Link
        $('.prev-unit, .next-unit').on('click', function() {
          $('#date_range input').val($(this).data('range'));
          $("#frm_summary_filter").submit();
          
          return false;
        });

      },

      init: function() {
        this.initDateRanger();
      }
    },

    init: function () {
      this.filter.init();
    }
  };

  return fn;
});