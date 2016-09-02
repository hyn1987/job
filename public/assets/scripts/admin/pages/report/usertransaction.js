/**
 * report/transactions.js
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
              'This Month': [moment().startOf('month'), moment().endOf('month')],
              'This Year': [moment().startOf('year'), moment().endOf('year')],
            },
            separator: ' to ',
            startDate: moment(date_from),
            endDate: moment(date_to),
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

            $("#frm_transactions_filter").submit();
          }
        ); 

        // Previous, Next Range Link
        $('.prev-unit, .next-unit').on('click', function() {
          $('#date_range input').val($(this).data('range'));
          $("#frm_transactions_filter").submit();
          
          return false;
        });

      },

      initContractFilter: function() {
        $('select.contract-filter').selectpicker({showIcon: false, width: true});
        $('select.contract-filter').on('change', function(){
          $("#frm_transactions_filter").submit();
        });
      }, 

      initTransactionTypeFilter: function() {
        $('#transaction_type').on('change', function(){
          $("#frm_transactions_filter").submit();
        });
      }, 

      init: function() {
        this.initDateRanger();
        this.initContractFilter();
        this.initTransactionTypeFilter();
      }
    },

    init: function () {
      this.filter.init();
    }
  };

  return fn;
});