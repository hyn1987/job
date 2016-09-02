/**
 * transaction.js - Admin / Report / Transaction
 */

define(['notify', 'moment', 'daterangepicker'], function (nt, moment) {

	var fn = {

		nav: {

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
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
            minDate: config.minDate,
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
          }
        );
			},

			init: function() {
				this.initDateRanger();
			}
		},

		init: function() {
			this.nav.init();
		}
	};

	return fn;

});