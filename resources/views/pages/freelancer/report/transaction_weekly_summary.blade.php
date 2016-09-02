<?php
/**
 * Weekly Summary Page (report/weekly-summary)
 *
 * @author  - Ri Chol Min
 */

use Wawjob\Transaction;
?>
@extends('layouts/freelancer/report')

@section('additional-css')
<link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css') }}">
<link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}">
@endsection

@section('content')
<div class="title-section">
  <span class="title">{{ trans('page.' . $page . '.title') }}</span>
</div>
<div class="page-content-section freelancer-report-page report-weekly-summary-page">
  <div class="filter-section clearfix">
    <form id="frm_summary_filter" method="POST">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      {{ show_messages() }}
      <div class="date-filter-section form-group pull-left">
        <div class="date-filter">
          @if ($prev)
          <a class="prev-unit" href="#" data-range="{{$prev}}"><i class="glyphicon glyphicon-chevron-left"></i></a>
          @endif
          <div class="input-group" id="date_range">
            @include("pages.snippet.daterange")
            <span class="input-group-btn">
              <button class="btn default date-range-toggle" type="button"><i class="fa fa-calendar"></i></button>
            </span>
          </div>
          @if ($next)
          <a class="next-unit" href="#" data-range="{{$next}}"><i class="glyphicon glyphicon-chevron-right"></i></a>
          @endif
        </div>
      </div>
    </form>
  </div><!-- END OF .filter-section -->

  <div class="weekly-summary-section table-scrollable">
    <div class="summary-section section">
      <div class="section-content">
        <div class="row">
          <div class="col-sm-7 col-xs-12">
            <div class="info-line row">
              <div class="col-xs-9">
                {{ trans('common.timesheet') }} <strong>{{ formatMinuteInterval($total['mins']) }} hrs</strong>:
              </div>
              <div class="col-xs-3 text-right">
                <strong>${{ formatCurrency($total['amount']) }} </strong>
              </div>
            </div>
            <div class="info-line row">
              <div class="col-xs-9">{{ trans('report.fixed_price_and_other_payments') }}:</div>
              <div class="col-xs-3 text-right">
                <strong>
                  {{ $total['others']<0? '($'.formatCurrency(abs($total['others'])).')' : '$'.formatCurrency($total['others']).'' }}
                </strong>
              </div>
            </div>

            <div class="info-line row">
              <div class="divider"></div>
            </div>

            <div class="info-line row last">
              <div class="total-amount col-xs-12 text-right">
                <strong>
                  {{ $total['others']+$total['amount']<0? '($'.formatCurrency(abs($total['others']+$total['amount'])).')' : '$'.formatCurrency($total['others']+$total['amount']).'' }}
                </strong>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="timesheet-section section">
      <div class="section-title">{{ trans('common.timesheet') }}</div>
      <div class="section-content">
        <table class="table">
          <thead>
            <tr>
              <th>{{ trans('common.word.contract') }}</th>
              @for($offset = 0; $offset < 7; $offset++)
              <th width="8%" class="text-center">
                <?php $one_date = date_add(date_create($dates['from']), date_interval_create_from_date_string("{$offset} days")); ?>
                <div>{{ date_format($one_date, 'D') }}</div>
                <div>{{ date_format($one_date, 'n/j') }}</div>
              </th>
              @endfor
              <th>{{ trans('report.hours') }}</th>
              <th class="text-right">{{ trans('report.pending') }}</th>
            </tr>
          </thead>
          <tbody>

            @if ( count($timesheets) < 1 )
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
                <td>
                   {{ formatMinuteInterval($cts['mins']) }}
                </td>
                <td class="text-right">
                   ${{ formatCurrency($cts['amount']) }}
                </td>
              </tr>
              @endforeach
            @endif

          </tbody>
        </table>
      </div>
    </div>

    <div class="fixed-other-section section">
      <div class="section-title">{{ trans('report.fixed_price_and_other_payments') }}</div>
      <div class="section-content table-scrollable">
        <table class="table">
          <thead>
            <tr>
              <th>{{ trans('common.word.contract') }}</th>
              <th>{{ trans('report.date') }}</th>
              <th>{{ trans('report.type') }}</th>
              <th>{{ trans('report.description') }}</th>
              <th class="text-right">{{ trans('report.amount') }}</th>
            </tr>
          </thead>
          <tbody>

            @if (!count($others))
              <tr>
                <td colspan="4">
                  <div class="empty-data-section">{{ trans('report.no_time_logged') }}</div>
                </td>
              </tr>
            @else
              @foreach ($others as $d)
              <tr class="status-{{ strtolower($d->status_string()) }}">
                <td>
                   {{ $d->contractor_fullname }}
                </td>
                <td>
                   {{ $d->status==Transaction::STATUS_AVAILABLE? date_format(date_create($d->done_at), "M d, Y"): date_format(date_create($d->created_at), "M d, Y") }}
                </td>
                <td>
                  {{ $d->type_string() }}
                </td>
                <td>
                   {!! $d->desc !!}
                </td>
                <td class="text-right">
                  {{ $d->amount>0? '$'.formatCurrency($d->amount).'' : '($'.formatCurrency(abs($d->amount)).')' }}
                </td>
              </tr>
              @endforeach
            @endif

          </tbody>
        </table>
      </div>
    </div>

  </div><!-- END OF .timesheet-section -->
</div>
@endsection