<?php
/**
 * Transaction History Page (report/transaction-history)
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
  <div class="balance pull-right">
     {{ trans('common.balance') }} : {{ $balance<0? '($'.formatCurrency(abs($balance)).')' : '$'.formatCurrency($balance) }}
  </div>
</div>
<div class="page-content-section freelancer-report-page report-transactions-page">
  <div class="filter-section clearfix">
    <form id="frm_transactions_filter" method="POST">
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

      <div class="contract-filter-section">
        @include("pages.freelancer.report.section.contract_selector")
      </div>

      <div class="transaction-type-section clearfix">
        <div class="section-content pull-right">
          <select class="form-control" id="transaction_type" name="transaction_type" placeholder="Transaction Type">
            <option value="all" {{ $type=='all'? 'selected':'' }}>{{ trans('report.all_transactions') }}</option>
            <option value="{{ Transaction::TYPE_FIXED }}" {{ $type==(string)Transaction::TYPE_FIXED? 'selected':'' }}>{{ trans('common.fixed_price') }}</option>
            <option value="{{ Transaction::TYPE_HOURLY }}" {{ $type==(string)Transaction::TYPE_HOURLY? 'selected':'' }}>{{ trans('common.hourly') }}</option>
            <option value="{{ Transaction::TYPE_BONUS }}" {{ $type==(string)Transaction::TYPE_BONUS? 'selected':'' }}>{{ trans('common.bonus') }}</option>
            <option value="{{ Transaction::TYPE_CHARGE }}" {{ $type==(string)Transaction::TYPE_CHARGE? 'selected':'' }}>{{ trans('common.charge') }}</option>
            <option value="{{ Transaction::TYPE_WITHDRAWAL }}" {{ $type==(string)Transaction::TYPE_WITHDRAWAL? 'selected':'' }}>{{ trans('common.withdrawal') }}</option>
            <option value="{{ Transaction::TYPE_REFUND }}" {{ $type==(string)Transaction::TYPE_REFUND? 'selected':'' }}>{{ trans('common.refund') }}</option>
          </select>
        </div>
      </div>
      

    </form>
  </div><!-- END OF .filter-section -->

  <div class="transactions-section table-scrollable">
    <table class="table">
      <thead>
        <tr>
          <th>#ID</th>
          <th>{{ trans('report.date') }}</th>
          <th>{{ trans('report.type') }}</th>
          <th>{{ trans('report.description') }}</th>
          <th>{{ trans('report.client') }}</th>
          <th class="text-right">{{ trans('report.amount') }}</th>
        </tr>
      </thead>
      <tbody>
        @if (!count($transactions))
          <tr>
            <td colspan="6">
              <div class="empty-data-section">{{ trans('report.no_transactions') }}</div>
            </td>
          </tr>
        @else
          @foreach ($transactions as $t)
          <tr class="status-{{ strtolower($t->status_string()) }}">
            <td>
               {{ $t->id }}
            </td>
            <td>
              {{ $t->status==Transaction::STATUS_AVAILABLE? date_format(date_create($t->done_at), "M d, Y") : date_format(date_create($t->created_at), "M d, Y") }}
            </td>
            <td>{{ $t->type_string() }}</td>
            <td>{!! $t->desc !!}</td>
            <td>{{ $t->buyer_fullname }}</td>
            <td class="text-right">
               {{ $t->amount<0? '($'.formatCurrency(abs($t->amount)).')' : '$'.formatCurrency($t->amount) }}
            </td>
          </tr>
          @endforeach
        @endif

      </tbody>
    </table>
  </div><!-- END OF .transactions-section -->

  <div class="statement-section row">
    <div class="col-sm-offset-8 col-sm-4 col-xs-12">
      <div class="statement-label">
        <div><strong>{{ trans('report.statement_period') }}</strong></div>
        <div>{{ $dates['from']." - ".$dates['to'] }}</div>
      </div>
      <div class="statement-content">
        <table class="">
          <tbody>
            <tr>
              <td class="info-label"><strong>{{ trans('report.beginning_balance') }}</strong></td>
              <td class="amount"><strong>
                {{ $statement['beginning']<0? '($'.formatCurrency(abs($statement['beginning'])).')' : '$'.formatCurrency($statement['beginning']) }}
              </strong></td>
            </tr>
            <tr>
              <td class="info-label">{{ trans('report.total_debits') }}</td>
              <td class="amount">
                {{ $statement['debits']<0? '($'.formatCurrency(abs($statement['debits'])).')' : '$'.formatCurrency($statement['debits']) }}
              </td>
            </tr>
            <tr>
              <td class="info-label">{{ trans('report.total_credits') }}</td>
              <td class="amount">
                {{ $statement['credits']<0? '($'.formatCurrency(abs($statement['credits'])).')' : '$'.formatCurrency($statement['credits']) }}
              </td>
            </tr>
            <tr>
              <td class="info-label">{{ trans('report.total_change') }}</td>
              <td class="amount">
                {{ $statement['change']<0? '($'.formatCurrency(abs($statement['change'])).')' : '$'.formatCurrency($statement['change']) }}
              </td>
            </tr>
            <tr>
              <td class="info-label last"><strong>{{ trans('report.ending_balance') }}</strong></td>
              <td class="amount last"><strong>
                {{ $statement['ending']<0? '($'.formatCurrency(abs($statement['ending'])).')' : '$'.formatCurrency($statement['ending']) }}
              </strong></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection