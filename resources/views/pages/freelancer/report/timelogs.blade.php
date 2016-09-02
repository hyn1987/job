<?php
/**
 * TimeLog Page (report/timelogs)
 *
 * @author  - CholMin Ri
 */
?>
@extends('layouts/freelancer/report')

@section('additional-css')
<link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css') }}">
<link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}">
@endsection

@section('content')
{{ show_messages() }}
<div class="tab-section">
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#work_in_progress" aria-controls="work_in_progress" role="tab" data-toggle="tab">{{ trans('report.work_in_progress') }}<div class="amount-display">${{formatCurrency($total['amount'])}}</div></a></li>    
    <li role="presentation"><a href="#pending" aria-controls="pending" role="tab" data-toggle="tab">{{ trans('report.pending') }}<div class="amount-display">${{formatCurrency($last_total['amount'])}}</div></a></li>
    <li role="presentation"><a href="#available" aria-controls="available" role="tab" data-toggle="tab">{{ trans('report.available') }}<div class="amount-display">$0.00</div><div class="info-display">{{ trans('report.last_payment') }}: ${{ formatCurrency($last_withdrawl_amount) }}</div></a></li>
  </ul>
</div>
<div class="page-content-section freelancer-report-page report-weekly-summary-page">
  
  <div class="weekly-summary-section table-scrollable">

    <!-- Tab panes -->
    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="work_in_progress">
        <div class="manual-alert">{!! trans('report.includes_n_hrs_manual_time', ['n' => formatMinuteInterval($manual_hours)]) !!}</div>
        <div class="timesheet-section section">
        <?php $start_date = date_create($dates['from']); ?>
        <?php $end_date = date_create($dates['to']); ?>
          <div class="section-title">{{ trans('report.timesheet_for_period', ['period' => date_format($start_date, 'M d').' - '.date_format($end_date, 'M d')]) }} ({{ trans('contract.this_week') }})</div>
          <div class="section-content">
            <table class="table">
              <thead>
                <tr>
                  <th width="25%">{{ trans('report.job') }}</th>
                  @for($offset = 0; $offset < 7; $offset++)
                  <th width="8%" class="text-center">
                    <?php $one_date = date_add(date_create($dates['from']), date_interval_create_from_date_string("{$offset} days")); ?>
                    <div>{{ date_format($one_date, 'D') }}</div>
                    <div>{{ date_format($one_date, 'M d') }}</div>
                  </th>
                  @endfor
                  <th>{{ trans('report.hours') }}</th>
                  <th>{{ trans('contract.rate') }}</th>
                  <th class="text-right">{{ trans('report.amount') }}</th>
                </tr>
              </thead>
              <tbody>

                @if ( count($timesheets) == 0 )
                  <tr>
                    <td colspan="10">
                      <div class="empty-data-section">{{ trans('report.no_matching_records') }}</div>
                    </td>
                  </tr>
                @else
                  @foreach ($timesheets as $cid=>$cts)
                  <tr>
                    <td>
                       {{ $cts['contract'] }}
                    </td>
                    @for($offset = 1; $offset <= 7; $offset++)
                    <td width="8%" class="text-center">
                      {{ isset($cts['week'][$offset])? formatMinuteInterval($cts['week'][$offset]->mins): '-' }}
                    </td>
                    @endfor
                    <td>{{ formatMinuteInterval($cts['mins']) }}</td>
                    <td class="text-right">${{ formatCurrency($cts['rate']) }}</td>
                    <td class="text-right">${{ formatCurrency($cts['amount']) }}</td>
                  </tr>
                  @endforeach
                  <tr>
                    <td colspan="8"></td>
                    <td><strong>{{formatMinuteInterval($total['mins'])}}</strong></td>
                    <td></td>
                    <td class="text-right"><strong>${{formatCurrency($total['amount'])}}</strong></td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div> 
      </div>
      <div role="tabpanel" class="tab-pane" id="pending">
        <div class="timesheet-section section">
        <?php $start_date = date_add(date_create($dates['from']), date_interval_create_from_date_string("-7 days")); ?>
        <?php $end_date = date_add(date_create($dates['from']), date_interval_create_from_date_string("-1 days")); ?>
          <div class="section-title">Timesheet for {{ date_format($start_date, 'M d') }} - {{ date_format($end_date, 'M d') }} ({{ trans('contract.last_week') }})</div>
          <div class="section-content">
            <table class="table">
              <thead>
                <tr>
                  <th width="25%">{{ trans('report.job') }}</th>
                  @for($offset = -7; $offset < 0; $offset++)
                  <th width="8%" class="text-center">
                    <?php $one_date = date_add(date_create($dates['from']), date_interval_create_from_date_string("{$offset} days")); ?>
                    <div>{{ date_format($one_date, 'D') }}</div>
                    <div>{{ date_format($one_date, 'M d') }}</div>
                  </th>
                  @endfor
                  <th>{{ trans('report.hours') }}</th>
                  <th>{{ trans('contract.rate') }}</th>
                  <th class="text-right">{{ trans('report.amount') }}</th>
                </tr>
              </thead>
              <tbody>

                @if ( count($last_timesheets) == 0 )
                  <tr>
                    <td colspan="10">
                      <div class="empty-data-section">{{ trans('report.no_matching_records') }}</div>
                    </td>
                  </tr>
                @else
                  @foreach ($last_timesheets as $cid=>$cts)
                  <tr>
                    <td>
                       {{ $cts['contract'] }}
                    </td>
                    @for($offset = 1; $offset <= 7; $offset++)
                    <td width="8%" class="text-center">
                      {{ isset($cts['week'][$offset])? formatMinuteInterval($cts['week'][$offset]->mins): '-' }}
                    </td>
                    @endfor
                    <td>
                       {{ formatMinuteInterval($cts['mins']) }}
                    </td>
                    <td class="text-right">
                       ${{ formatCurrency($cts['rate']) }}
                    </td>
                    <td class="text-right">
                       ${{ formatCurrency($cts['amount']) }}
                    </td>
                  </tr>
                  @endforeach
                  <tr>
                    <td colspan="8"></td>
                    <td><strong>{{formatMinuteInterval($last_total['mins'])}}</strong></td>
                    <td></td>
                    <td class="text-right"><strong>${{formatCurrency($last_total['amount'])}}</strong></td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div role="tabpanel" class="tab-pane" id="available">...</div>
    </div>  

  </div><!-- END OF .timesheet-section -->
</div>
@endsection